<?php

declare(strict_types=1);

use It4eb\Fakturownia\Data\Responses\DepartmentResponse;
use It4eb\Fakturownia\Data\Responses\InvoiceResponse;
use It4eb\Fakturownia\Enums\InvoiceStatus;
use It4eb\Fakturownia\Requests\Departments\ListDepartments;
use It4eb\Fakturownia\Requests\Invoices\ChangeInvoiceStatus;
use It4eb\Fakturownia\Requests\Invoices\CreateInvoice;
use It4eb\Fakturownia\Requests\Invoices\GetInvoice;
use It4eb\Fakturownia\Requests\Invoices\GetInvoicePdf;
use It4eb\Fakturownia\Requests\Invoices\UpdateInvoice;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

// fakturownia() and sampleInvoice() helpers live in tests/Pest.php.

it('sends the api_token in the body and wraps create payload in "invoice"', function () {
    $mock = new MockClient([
        CreateInvoice::class => MockResponse::make(['id' => 99, 'view_url' => 'https://x', 'positions' => [], 'recipients' => []], 201),
    ]);

    $response = fakturownia($mock)->send(new CreateInvoice(sampleInvoice()));
    $body = $response->getPendingRequest()->body()->all();

    expect($response->getPendingRequest()->getMethod())->toBe(Method::POST)
        ->and($body)->toHaveKey('api_token', 'TESTTOKEN')
        ->and($body)->toHaveKey('invoice')
        ->and($body['invoice']['number'])->toBe('FV/1/2026')
        ->and($body['invoice']['positions'])->toHaveCount(1)
        ->and($body['invoice'])->not->toHaveKey('api_token'); // token is a sibling, not nested
});

it('returns a typed InvoiceResponse from create', function () {
    $mock = new MockClient([
        CreateInvoice::class => MockResponse::make([
            'id' => 99,
            'view_url' => 'https://app/x',
            'positions' => [['id' => 7]],
            'recipients' => [['id' => 3]],
        ], 201),
    ]);

    $dto = fakturownia($mock)->invoices()->create(sampleInvoice());

    expect($dto)->toBeInstanceOf(InvoiceResponse::class)
        ->and($dto->id)->toBe(99)
        ->and($dto->viewUrl)->toBe('https://app/x')
        ->and($dto->positionIds())->toBe([7])
        ->and($dto->recipientIds())->toBe([3]);
});

it('sends the api_token as a query param for GET and supports include_positions', function () {
    $mock = new MockClient([
        GetInvoice::class => MockResponse::make(['id' => 10, 'positions' => [], 'recipients' => []], 200),
    ]);

    $response = fakturownia($mock)->send(new GetInvoice(10, includePositions: true));
    $query = $response->getPendingRequest()->query()->all();

    expect($response->getPendingRequest()->getMethod())->toBe(Method::GET)
        ->and($query)->toMatchArray([
            'api_token' => 'TESTTOKEN',
            'include_positions' => 'true',
        ])
        ->and($response->getPendingRequest()->body())->toBeNull();
});

it('updates via PUT with the api_token in the body', function () {
    $mock = new MockClient([
        UpdateInvoice::class => MockResponse::make(['id' => 10, 'positions' => [], 'recipients' => []], 200),
    ]);

    $response = fakturownia($mock)->send(new UpdateInvoice(10, ['status' => 'paid', 'paid' => 100.0]));
    $body = $response->getPendingRequest()->body()->all();

    expect($response->getPendingRequest()->getMethod())->toBe(Method::PUT)
        ->and($body)->toHaveKey('api_token', 'TESTTOKEN')
        ->and($body['invoice'])->toBe(['status' => 'paid', 'paid' => 100.0]);
});

it('sends change_status with status and token in the query (no body)', function () {
    $mock = new MockClient([
        ChangeInvoiceStatus::class => MockResponse::make([], 200),
    ]);

    $response = fakturownia($mock)->send(new ChangeInvoiceStatus(10, InvoiceStatus::PAID));
    $query = $response->getPendingRequest()->query()->all();

    expect($response->getPendingRequest()->getMethod())->toBe(Method::POST)
        ->and($query)->toMatchArray(['api_token' => 'TESTTOKEN', 'status' => 'paid']);
});

it('passes through raw PDF bytes', function () {
    $mock = new MockClient([
        GetInvoicePdf::class => MockResponse::make('%PDF-1.7 bytes', 200),
    ]);

    expect(fakturownia($mock)->invoices()->pdf(5))->toBe('%PDF-1.7 bytes');
});

it('maps the departments list to typed DTOs', function () {
    $mock = new MockClient([
        ListDepartments::class => MockResponse::make([
            ['id' => 1, 'name' => 'Główny', 'bank_account' => 'PL01', 'bank_account_currency' => 'PLN'],
            ['id' => 2, 'name' => 'Drugi'],
        ], 200),
    ]);

    $departments = fakturownia($mock)->departments()->list();

    expect($departments)->toHaveCount(2)
        ->and($departments[0])->toBeInstanceOf(DepartmentResponse::class)
        ->and($departments[0]->id)->toBe(1)
        ->and($departments[0]->bankAccountCurrency)->toBe('PLN');
});
