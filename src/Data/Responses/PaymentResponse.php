<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Responses;

use Spatie\LaravelData\Data;

final class PaymentResponse extends Data
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?float $price = null,
        public ?int $invoiceId = null,
        public ?bool $paid = null,
        public array $raw = [],
    ) {}

    /**
     * @param  array<string, mixed>  $json
     */
    public static function fromApi(array $json): self
    {
        return new self(
            id: (int) ($json['id'] ?? 0),
            name: $json['name'] ?? null,
            price: isset($json['price']) ? (float) $json['price'] : null,
            invoiceId: isset($json['invoice_id']) ? (int) $json['invoice_id'] : null,
            paid: isset($json['paid']) ? (bool) $json['paid'] : null,
            raw: $json,
        );
    }
}
