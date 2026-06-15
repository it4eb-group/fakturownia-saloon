<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Responses;

use Spatie\LaravelData\Data;

final class PositionResponse extends Data
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?float $quantity = null,
        public ?string $quantityUnit = null,
        public ?float $totalPriceGross = null,
        public int|string|null $tax = null,
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
            quantity: isset($json['quantity']) ? (float) $json['quantity'] : null,
            quantityUnit: $json['quantity_unit'] ?? null,
            totalPriceGross: isset($json['total_price_gross']) ? (float) $json['total_price_gross'] : null,
            tax: $json['tax'] ?? null,
            raw: $json,
        );
    }
}
