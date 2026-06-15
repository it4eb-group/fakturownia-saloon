<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Payments;

use It4eb\Fakturownia\Data\Responses\PaymentResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class ListPayments extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $filters  e.g. ['page' => 1, 'include' => 'invoice']
     */
    public function __construct(private readonly array $filters = []) {}

    public function resolveEndpoint(): string
    {
        return '/banking/payments.json';
    }

    /**
     * @return list<PaymentResponse>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $payment): PaymentResponse => PaymentResponse::fromApi($payment),
            $response->json(),
        );
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
