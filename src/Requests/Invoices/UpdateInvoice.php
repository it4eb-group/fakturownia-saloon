<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use It4eb\Fakturownia\Data\Responses\InvoiceResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Updates an invoice. Accepts an already-serialised invoice payload array so
 * it can carry either a full reconciled invoice (positions with id/_destroy)
 * or a partial patch (e.g. status + paid).
 */
final class UpdateInvoice extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $invoice
     */
    public function __construct(
        private readonly int $id,
        private readonly array $invoice,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->id}.json";
    }

    public function createDtoFromResponse(Response $response): InvoiceResponse
    {
        return InvoiceResponse::fromApi($response->json());
    }

    protected function defaultBody(): array
    {
        return ['invoice' => $this->invoice];
    }
}
