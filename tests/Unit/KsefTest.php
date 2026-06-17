<?php

declare(strict_types=1);

use It4eb\Fakturownia\Data\Responses\InvoiceResponse;
use It4eb\Fakturownia\Enums\GovStatus;
use It4eb\Fakturownia\Requests\Invoices\GetInvoiceKsefUpo;
use It4eb\Fakturownia\Requests\Invoices\GetInvoiceKsefXml;
use It4eb\Fakturownia\Requests\Invoices\SendInvoiceToKsef;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

// The fakturownia() helper is declared in ConnectorTest.php and shared across the suite.

it('sends to KSeF as a body-less GET with send_to_ksef=yes and the token in the query', function () {
    $mock = new MockClient([
        SendInvoiceToKsef::class => MockResponse::make(['id' => 42, 'positions' => [], 'recipients' => []], 200),
    ]);

    $pending = fakturownia($mock)->send(new SendInvoiceToKsef(42))->getPendingRequest();

    expect($pending->getMethod())->toBe(Method::GET)
        ->and($pending->getUrl())->toBe('https://testdomain.fakturownia.pl/invoices/42.json')
        ->and($pending->query()->all())->toMatchArray([
            'api_token' => 'TESTTOKEN',
            'send_to_ksef' => 'yes',
        ])
        ->and($pending->body())->toBeNull();
});

it('returns a typed InvoiceResponse carrying gov_status and gov_id', function () {
    $mock = new MockClient([
        SendInvoiceToKsef::class => MockResponse::make([
            'id' => 42,
            'gov_status' => 'processing',
            'gov_id' => '1234567890',
            'positions' => [],
            'recipients' => [],
        ], 200),
    ]);

    $dto = fakturownia($mock)->invoices()->sendToKsef(42);

    expect($dto)->toBeInstanceOf(InvoiceResponse::class)
        ->and($dto->id)->toBe(42)
        ->and($dto->govStatus)->toBe(GovStatus::PROCESSING)
        ->and($dto->govId)->toBe('1234567890');
});

it('requests the gov attachment as XML and passes through the raw bytes', function () {
    $xml = '<?xml version="1.0"?><Faktura/>';
    $mock = new MockClient([
        GetInvoiceKsefXml::class => MockResponse::make($xml, 200),
    ]);

    $pending = fakturownia($mock)->send(new GetInvoiceKsefXml(5))->getPendingRequest();

    expect($pending->getMethod())->toBe(Method::GET)
        ->and($pending->getUrl())->toBe('https://testdomain.fakturownia.pl/invoices/5/attachment')
        ->and($pending->query()->all())->toMatchArray([
            'api_token' => 'TESTTOKEN',
            'kind' => 'gov',
        ])
        ->and($pending->headers()->get('Accept'))->toBe('application/xml')
        ->and($pending->body())->toBeNull();

    expect(fakturownia($mock)->invoices()->ksefXml(5))->toBe($xml);
});

it('requests the gov_upo attachment as XML and passes through the raw bytes', function () {
    $upo = '<?xml version="1.0"?><Potwierdzenie/>';
    $mock = new MockClient([
        GetInvoiceKsefUpo::class => MockResponse::make($upo, 200),
    ]);

    $pending = fakturownia($mock)->send(new GetInvoiceKsefUpo(5))->getPendingRequest();

    expect($pending->getMethod())->toBe(Method::GET)
        ->and($pending->getUrl())->toBe('https://testdomain.fakturownia.pl/invoices/5/attachment')
        ->and($pending->query()->all())->toMatchArray([
            'api_token' => 'TESTTOKEN',
            'kind' => 'gov_upo',
        ])
        ->and($pending->headers()->get('Accept'))->toBe('application/xml')
        ->and($pending->body())->toBeNull();

    expect(fakturownia($mock)->invoices()->ksefUpo(5))->toBe($upo);
});
