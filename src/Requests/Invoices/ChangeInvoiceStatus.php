<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use It4eb\Fakturownia\Enums\InvoiceStatus;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Changes invoice status. This endpoint takes the status as a query parameter
 * and carries no body, so the api_token is added to the query string too.
 */
final class ChangeInvoiceStatus extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $id,
        private readonly InvoiceStatus $status,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->id}/change_status.json";
    }

    protected function defaultQuery(): array
    {
        return ['status' => $this->status->value];
    }
}
