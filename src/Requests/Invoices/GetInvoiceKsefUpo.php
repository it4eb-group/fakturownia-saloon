<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Downloads the KSeF UPO (official confirmation of receipt) for an invoice.
 * Response body is raw XML.
 */
final class GetInvoiceKsefUpo extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->id}/attachment";
    }

    protected function defaultQuery(): array
    {
        return ['kind' => 'gov_upo'];
    }

    protected function defaultHeaders(): array
    {
        return ['Accept' => 'application/xml'];
    }
}
