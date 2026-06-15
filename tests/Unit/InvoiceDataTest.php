<?php

declare(strict_types=1);

use It4eb\Fakturownia\Data\Requests\InvoiceData;
use It4eb\Fakturownia\Data\Requests\PartyData;
use It4eb\Fakturownia\Data\Requests\PositionData;
use It4eb\Fakturownia\Data\Requests\RecipientData;
use It4eb\Fakturownia\Enums\InvoiceKind;
use It4eb\Fakturownia\Enums\InvoiceStatus;
use It4eb\Fakturownia\Enums\NpTaxKind;
use It4eb\Fakturownia\Exceptions\InvalidInvoiceDataException;

function makeInvoice(array $overrides = []): InvoiceData
{
    return new InvoiceData(...array_merge([
        'kind' => InvoiceKind::VAT,
        'issueDate' => '2026-06-15',
        'sellDate' => '2026-06-15',
        'seller' => new PartyData(name: 'Acme', taxNo: '1234567890', street: 'Main 1', postCode: '00-001', city: 'City', country: 'PL'),
        'buyer' => new PartyData(name: 'Klient', taxNo: '0987654321'),
        'positions' => [new PositionData(name: 'Moskitiera', quantity: 2, totalPriceGross: 246.0, tax: 23, quantityUnit: 'szt')],
    ], $overrides));
}

it('serialises a minimal invoice to the flat Fakturownia wire shape', function () {
    $data = makeInvoice()->toApiArray();

    expect($data['kind'])->toBe('vat')
        ->and($data['issue_date'])->toBe('2026-06-15')
        ->and($data['sell_date'])->toBe('2026-06-15')
        ->and($data['seller_name'])->toBe('Acme')
        ->and($data['seller_tax_no'])->toBe('1234567890')
        ->and($data['buyer_name'])->toBe('Klient')
        ->and($data['buyer_tax_no'])->toBe('0987654321')
        ->and($data['buyer_company'])->toBe('1')
        ->and($data['currency'])->toBe('PLN')
        ->and($data['lang'])->toBe('pl')
        ->and($data['payment_type'])->toBe('transfer');

    expect($data['positions'])->toHaveCount(1)
        ->and($data['positions'][0])->toMatchArray([
            'name' => 'Moskitiera',
            'quantity' => 2.0,
            'total_price_gross' => 246.0,
            'tax' => 23,
            'quantity_unit' => 'szt',
        ]);

    // not paid, no recipients, no KSeF flags
    expect($data)->not->toHaveKey('paid')
        ->and($data)->not->toHaveKey('recipients')
        ->and($data)->not->toHaveKey('np_tax_kind');
});

it('includes paid when status is paid', function () {
    $data = makeInvoice(['status' => InvoiceStatus::PAID, 'pricePaid' => 246.0])->toApiArray();

    expect($data['status'])->toBe('paid')
        ->and($data['paid'])->toBe(246.0);
});

it('serialises a numeric payment deadline as payment_to_kind', function () {
    $data = makeInvoice(['paymentDeadline' => 14])->toApiArray();

    expect($data['payment_to_kind'])->toBe(14)
        ->and($data)->not->toHaveKey('payment_to');
});

it('serialises a date payment deadline as other_date + payment_to', function () {
    $data = makeInvoice(['paymentDeadline' => '2026-07-01'])->toApiArray();

    expect($data['payment_to_kind'])->toBe('other_date')
        ->and($data['payment_to'])->toBe('2026-07-01');
});

it('serialises recipients only when present', function () {
    $data = makeInvoice([
        'recipients' => [
            new RecipientData(name: 'Odbiorca', company: true, id: 42, destroy: true),
        ],
    ])->toApiArray();

    expect($data['recipients'])->toHaveCount(1)
        ->and($data['recipients'][0])->toMatchArray([
            'company' => true,
            'name' => 'Odbiorca',
            'id' => 42,
            '_destroy' => 1,
        ]);
});

it('serialises KSeF and split payment fields', function () {
    $data = makeInvoice([
        'npTaxKind' => NpTaxKind::EXPORT_SERVICE_EU,
        'reverseCharge' => true,
        'splitPayment' => true,
        'govSaveAndSend' => true,
        'invoiceIds' => [101, 102],
    ])->toApiArray();

    expect($data['np_tax_kind'])->toBe('export_service_eu')
        ->and($data['reverse_charge'])->toBeTrue()
        ->and($data['split_payment'])->toBe('1')
        ->and($data['gov_save_and_send'])->toBeTrue()
        ->and($data['invoice_ids'])->toBe([101, 102]);
});

it('serialises position id and _destroy for update reconciliation', function () {
    $position = new PositionData(name: 'Stare', quantity: 1, totalPriceGross: 10.0, id: 555, destroy: true);

    expect($position->toApiArray())->toMatchArray([
        'name' => 'Stare',
        'id' => 555,
        '_destroy' => 1,
    ]);
});

it('rejects an invoice without positions', function () {
    makeInvoice(['positions' => []]);
})->throws(InvalidInvoiceDataException::class);

it('rejects a malformed issue date', function () {
    makeInvoice(['issueDate' => '15-06-2026']);
})->throws(InvalidInvoiceDataException::class);

it('rejects a position with a non-positive quantity', function () {
    new PositionData(name: 'Zero', quantity: 0, totalPriceGross: 10.0);
})->throws(InvalidInvoiceDataException::class);

it('rejects a position with an empty name', function () {
    new PositionData(name: '  ', quantity: 1, totalPriceGross: 10.0);
})->throws(InvalidInvoiceDataException::class);
