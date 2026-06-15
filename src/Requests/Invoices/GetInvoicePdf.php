<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Downloads the invoice PDF. The response body is raw binary; read it with
 * $response->body() (string) or $response->stream().
 */
final class GetInvoicePdf extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->id}.pdf";
    }

    protected function defaultHeaders(): array
    {
        return ['Accept' => 'application/pdf'];
    }
}
