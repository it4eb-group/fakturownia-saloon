<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Resources;

use It4eb\Fakturownia\Data\Requests\CategoryData;
use It4eb\Fakturownia\Data\Responses\CategoryResponse;
use It4eb\Fakturownia\Requests\Categories\CreateCategory;
use It4eb\Fakturownia\Requests\Categories\DeleteCategory;
use It4eb\Fakturownia\Requests\Categories\GetCategory;
use It4eb\Fakturownia\Requests\Categories\ListCategories;
use It4eb\Fakturownia\Requests\Categories\UpdateCategory;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

final class CategoryResource extends BaseResource
{
    /**
     * @return list<CategoryResponse>
     */
    public function list(): array
    {
        return $this->connector->send(new ListCategories)->dtoOrFail();
    }

    public function get(int $id): CategoryResponse
    {
        return $this->connector->send(new GetCategory($id))->dtoOrFail();
    }

    /**
     * @param  CategoryData|array<string, mixed>  $category
     */
    public function create(CategoryData|array $category): CategoryResponse
    {
        $payload = $category instanceof CategoryData ? $category->toApiArray() : $category;

        return $this->connector->send(new CreateCategory($payload))->dtoOrFail();
    }

    /**
     * @param  CategoryData|array<string, mixed>  $category
     */
    public function update(int $id, CategoryData|array $category): CategoryResponse
    {
        $payload = $category instanceof CategoryData ? $category->toApiArray() : $category;

        return $this->connector->send(new UpdateCategory($id, $payload))->dtoOrFail();
    }

    public function delete(int $id): Response
    {
        return $this->connector->send(new DeleteCategory($id));
    }
}
