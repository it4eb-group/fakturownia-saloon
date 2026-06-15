<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Products;

use It4eb\Fakturownia\Data\Responses\ProductResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateProduct extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $product
     */
    public function __construct(private readonly array $product) {}

    public function resolveEndpoint(): string
    {
        return '/products.json';
    }

    public function createDtoFromResponse(Response $response): ProductResponse
    {
        return ProductResponse::fromApi($response->json());
    }

    protected function defaultBody(): array
    {
        return ['product' => $this->product];
    }
}
