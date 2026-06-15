<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Resources;

use It4eb\Fakturownia\Data\Requests\ProductData;
use It4eb\Fakturownia\Data\Responses\ProductResponse;
use It4eb\Fakturownia\Requests\Products\CreateProduct;
use It4eb\Fakturownia\Requests\Products\DeleteProduct;
use It4eb\Fakturownia\Requests\Products\GetProduct;
use It4eb\Fakturownia\Requests\Products\ListProducts;
use It4eb\Fakturownia\Requests\Products\UpdateProduct;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

final class ProductResource extends BaseResource
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<ProductResponse>
     */
    public function list(array $filters = []): array
    {
        return $this->connector->send(new ListProducts($filters))->dtoOrFail();
    }

    public function get(int $id, ?int $warehouseId = null): ProductResponse
    {
        return $this->connector->send(new GetProduct($id, $warehouseId))->dtoOrFail();
    }

    /**
     * @param  ProductData|array<string, mixed>  $product
     */
    public function create(ProductData|array $product): ProductResponse
    {
        $payload = $product instanceof ProductData ? $product->toApiArray() : $product;

        return $this->connector->send(new CreateProduct($payload))->dtoOrFail();
    }

    /**
     * @param  ProductData|array<string, mixed>  $product
     */
    public function update(int $id, ProductData|array $product): ProductResponse
    {
        $payload = $product instanceof ProductData ? $product->toApiArray() : $product;

        return $this->connector->send(new UpdateProduct($id, $payload))->dtoOrFail();
    }

    public function delete(int $id): Response
    {
        return $this->connector->send(new DeleteProduct($id));
    }
}
