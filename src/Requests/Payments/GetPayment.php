<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Payments;

use It4eb\Fakturownia\Data\Responses\PaymentResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetPayment extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/banking/payment/{$this->id}.json";
    }

    public function createDtoFromResponse(Response $response): PaymentResponse
    {
        return PaymentResponse::fromApi($response->json());
    }
}
