<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use It4eb\Fakturownia\Data\Requests\InvoiceData;
use It4eb\Fakturownia\Data\Responses\InvoiceResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateInvoice extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly InvoiceData $invoice) {}

    public function resolveEndpoint(): string
    {
        return '/invoices.json';
    }

    public function createDtoFromResponse(Response $response): InvoiceResponse
    {
        return InvoiceResponse::fromApi($response->json());
    }

    protected function defaultBody(): array
    {
        return ['invoice' => $this->invoice->toApiArray()];
    }
}
