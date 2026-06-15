<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteProduct extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/products/{$this->id}.json";
    }
}
