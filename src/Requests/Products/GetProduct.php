<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Products;

use It4eb\Fakturownia\Data\Responses\ProductResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetProduct extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $id,
        private readonly ?int $warehouseId = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/products/{$this->id}.json";
    }

    public function createDtoFromResponse(Response $response): ProductResponse
    {
        return ProductResponse::fromApi($response->json());
    }

    protected function defaultQuery(): array
    {
        return $this->warehouseId !== null ? ['warehouse_id' => $this->warehouseId] : [];
    }
}
