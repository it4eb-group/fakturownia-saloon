<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use It4eb\Fakturownia\Data\Responses\InvoiceResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * Submits an existing invoice to KSeF (Polish national e-invoicing). Fakturownia
 * exposes this as a flag on the invoice GET endpoint; the response carries the
 * updated gov_status / gov_id.
 */
final class SendInvoiceToKsef extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->id}.json";
    }

    public function createDtoFromResponse(Response $response): InvoiceResponse
    {
        return InvoiceResponse::fromApi($response->json());
    }

    protected function defaultQuery(): array
    {
        return ['send_to_ksef' => 'yes'];
    }
}
