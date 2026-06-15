<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteInvoice extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->id}.json";
    }
}
