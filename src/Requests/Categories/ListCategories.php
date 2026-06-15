<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Categories;

use It4eb\Fakturownia\Data\Responses\CategoryResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class ListCategories extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/categories.json';
    }

    /**
     * @return list<CategoryResponse>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $category): CategoryResponse => CategoryResponse::fromApi($category),
            $response->json(),
        );
    }
}
