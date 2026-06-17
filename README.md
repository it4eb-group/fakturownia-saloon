# it4eb-group/fakturownia-saloon

A [SaloonPHP](https://docs.saloon.dev) client for the **Fakturownia** (InvoiceOcean)
REST API, with typed [`spatie/laravel-data`](https://spatie.be/docs/laravel-data) DTOs,
strong validation and **KSeF** support. Works with **Laravel 11, 12 and 13** (PHP 8.2+).

## Why

The transport layer is Saloon (testable, retryable, middleware-friendly); request and
response payloads are typed DTOs with validation; credentials are constructor-injected,
so the same code serves a global account or per-tenant accounts.

## Usage

```php
use It4eb\Fakturownia\FakturowniaConnector;
use It4eb\Fakturownia\Data\Requests\{InvoiceData, PartyData, PositionData};
use It4eb\Fakturownia\Enums\InvoiceKind;

$fakturownia = new FakturowniaConnector(domain: 'my-account', apiToken: '...');
// or, in Laravel: app(FakturowniaConnector::class) / Fakturownia facade

$invoice = new InvoiceData(
    kind: InvoiceKind::VAT,
    issueDate: '2026-06-15',
    sellDate: '2026-06-15',
    seller: new PartyData(name: 'Acme sp. z o.o.', taxNo: '1234567890'),
    buyer: new PartyData(name: 'Klient', taxNo: '0987654321'),
    positions: [
        new PositionData(name: 'Moskitiera', quantity: 2, totalPriceGross: 246.00, tax: 23),
    ],
);

$response = $fakturownia->invoices()->create($invoice);   // InvoiceResponse
$pdf      = $fakturownia->invoices()->pdf($response->id);  // raw PDF bytes
$fakturownia->invoices()->sendByEmail($response->id);
```

### Resources

`invoices()`, `departments()`, `clients()`, `products()`, `payments()`, `categories()`.

Invoices also expose KSeF operations: `sendToKsef($id)`, `ksefXml($id)`, `ksefUpo($id)`,
plus the `gov_save_and_send`, `np_tax_kind`, `reverse_charge`, `split_payment` invoice fields.

## Authentication

The Fakturownia `api_token` is added automatically per request — into the JSON body for
write requests, otherwise as a `?api_token=` query parameter — by `ApiTokenAuthenticator`.

## Testing

Use Saloon's `MockClient` (framework-agnostic) or, in Laravel, `Saloon::fake([...])`:

```php
$mock = new \Saloon\Http\Faking\MockClient([
    CreateInvoice::class => \Saloon\Http\Faking\MockResponse::make(['id' => 1], 201),
]);
$connector->withMockClient($mock);
```

## Configuration (Laravel)

Reads `config/fakturownia.php` (`FAKTUROWNIA_TOKEN`, `FAKTUROWNIA_DOMAIN`). The
`FakturowniaConnector` is bound as a singleton; rebind it to source per-tenant credentials.
