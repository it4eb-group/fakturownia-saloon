<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Resources;

use It4eb\Fakturownia\Data\Requests\PaymentData;
use It4eb\Fakturownia\Data\Responses\PaymentResponse;
use It4eb\Fakturownia\Requests\Payments\CreatePayment;
use It4eb\Fakturownia\Requests\Payments\DeletePayment;
use It4eb\Fakturownia\Requests\Payments\GetPayment;
use It4eb\Fakturownia\Requests\Payments\ListPayments;
use It4eb\Fakturownia\Requests\Payments\UpdatePayment;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

final class PaymentResource extends BaseResource
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<PaymentResponse>
     */
    public function list(array $filters = []): array
    {
        return $this->connector->send(new ListPayments($filters))->dtoOrFail();
    }

    public function get(int $id): PaymentResponse
    {
        return $this->connector->send(new GetPayment($id))->dtoOrFail();
    }

    /**
     * @param  PaymentData|array<string, mixed>  $payment
     */
    public function create(PaymentData|array $payment): PaymentResponse
    {
        $payload = $payment instanceof PaymentData ? $payment->toApiArray() : $payment;

        return $this->connector->send(new CreatePayment($payload))->dtoOrFail();
    }

    /**
     * @param  PaymentData|array<string, mixed>  $payment
     */
    public function update(int $id, PaymentData|array $payment): PaymentResponse
    {
        $payload = $payment instanceof PaymentData ? $payment->toApiArray() : $payment;

        return $this->connector->send(new UpdatePayment($id, $payload))->dtoOrFail();
    }

    public function delete(int $id): Response
    {
        return $this->connector->send(new DeletePayment($id));
    }
}
