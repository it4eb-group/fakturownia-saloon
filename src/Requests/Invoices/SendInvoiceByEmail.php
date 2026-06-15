<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Invoices;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Emails the invoice to the buyer. Carries a JSON body so the api_token is
 * placed there; optional overrides (e.g. recipient address) may be passed.
 */
final class SendInvoiceByEmail extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $options  e.g. ['email_to' => 'a@b.c', 'email_pdf' => true]
     */
    public function __construct(
        private readonly int $id,
        private readonly array $options = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->id}/send_by_email.json";
    }

    protected function defaultBody(): array
    {
        return $this->options;
    }
}
