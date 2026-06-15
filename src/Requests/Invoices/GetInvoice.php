<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use It4eb\Fakturownia\Data\Responses\InvoiceResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetInvoice extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $id,
        private readonly bool $includePositions = false,
    ) {}

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
        return $this->includePositions ? ['include_positions' => 'true'] : [];
    }
}
