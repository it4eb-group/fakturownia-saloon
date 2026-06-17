<?php

declare(strict_types=1);

use It4eb\Fakturownia\Data\Requests\InvoiceData;
use It4eb\Fakturownia\Data\Requests\PartyData;
use It4eb\Fakturownia\Data\Requests\PositionData;
use It4eb\Fakturownia\Enums\InvoiceKind;
use It4eb\Fakturownia\FakturowniaConnector;
use Saloon\Http\Faking\MockClient;

/**
 * A connector bound to a deterministic test account, wired to the given mock.
 */
function fakturownia(MockClient $mock): FakturowniaConnector
{
    return (new FakturowniaConnector('testdomain', 'TESTTOKEN'))->withMockClient($mock);
}

/**
 * A minimal, valid invoice for request-shape assertions.
 */
function sampleInvoice(): InvoiceData
{
    return new InvoiceData(
        kind: InvoiceKind::VAT,
        issueDate: '2026-06-15',
        sellDate: '2026-06-15',
        number: 'FV/1/2026',
        seller: new PartyData(name: 'Acme', taxNo: '1234567890'),
        buyer: new PartyData(name: 'Klient', taxNo: '0987654321'),
        positions: [new PositionData(name: 'Item', quantity: 1, totalPriceGross: 100.0, tax: 23)],
    );
}
