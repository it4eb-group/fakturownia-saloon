<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use It4eb\Fakturownia\Data\Responses\InvoiceResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class ListInvoices extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $filters  e.g. ['period' => 'this_month', 'page' => 1, 'client_id' => 5]
     */
    public function __construct(private readonly array $filters = []) {}

    public function resolveEndpoint(): string
    {
        return '/invoices.json';
    }

    /**
     * @return list<InvoiceResponse>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $invoice): InvoiceResponse => InvoiceResponse::fromApi($invoice),
            $response->json(),
        );
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
