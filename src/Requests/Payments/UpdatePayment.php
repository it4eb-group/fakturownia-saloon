<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Payments;

use It4eb\Fakturownia\Data\Responses\PaymentResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class UpdatePayment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payment
     */
    public function __construct(
        private readonly int $id,
        private readonly array $payment,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/banking/payment/{$this->id}.json";
    }

    public function createDtoFromResponse(Response $response): PaymentResponse
    {
        return PaymentResponse::fromApi($response->json());
    }

    protected function defaultBody(): array
    {
        return ['banking_payment' => $this->payment];
    }
}
