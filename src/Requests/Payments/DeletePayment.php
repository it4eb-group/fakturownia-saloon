<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Payments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeletePayment extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/banking/payment/{$this->id}.json";
    }
}
