<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Categories;

use It4eb\Fakturownia\Data\Responses\CategoryResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetCategory extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/categories/{$this->id}.json";
    }

    public function createDtoFromResponse(Response $response): CategoryResponse
    {
        return CategoryResponse::fromApi($response->json());
    }
}
