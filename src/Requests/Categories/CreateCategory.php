<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Categories;

use It4eb\Fakturownia\Data\Responses\CategoryResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateCategory extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $category
     */
    public function __construct(private readonly array $category) {}

    public function resolveEndpoint(): string
    {
        return '/categories.json';
    }

    public function createDtoFromResponse(Response $response): CategoryResponse
    {
        return CategoryResponse::fromApi($response->json());
    }

    protected function defaultBody(): array
    {
        return ['category' => $this->category];
    }
}
