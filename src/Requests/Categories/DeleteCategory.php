<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Categories;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteCategory extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/categories/{$this->id}.json";
    }
}
