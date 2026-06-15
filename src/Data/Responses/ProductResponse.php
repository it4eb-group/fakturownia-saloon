<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Responses;

use Spatie\LaravelData\Data;

final class ProductResponse extends Data
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $code = null,
        public ?float $priceNet = null,
        public ?float $priceGross = null,
        public ?float $stockLevel = null,
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
            code: $json['code'] ?? null,
            priceNet: isset($json['price_net']) ? (float) $json['price_net'] : null,
            priceGross: isset($json['price_gross']) ? (float) $json['price_gross'] : null,
            stockLevel: isset($json['stock_level']) ? (float) $json['stock_level'] : null,
            raw: $json,
        );
    }
}
