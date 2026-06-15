<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use It4eb\Fakturownia\Enums\PaymentMethod;
use Spatie\LaravelData\Data;

final class PaymentData extends Data
{
    public function __construct(
        public float $price,
        public ?string $name = null,
        public ?int $invoiceId = null,
        public bool $paid = true,
        public ?PaymentMethod $kind = null,
        public ?string $paymentDate = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'price' => $this->price,
            'invoice_id' => $this->invoiceId,
            'paid' => $this->paid,
            'kind' => $this->kind?->value,
            'payment_date' => $this->paymentDate,
        ], static fn ($value): bool => $value !== null);
    }
}
