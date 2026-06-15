<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use Spatie\LaravelData\Data;

final class ProductData extends Data
{
    /**
     * @param  int|string|null  $tax  numeric VAT percent or a symbol ("zw", "np")
     */
    public function __construct(
        public ?string $name = null,
        public ?string $code = null,
        public ?float $priceNet = null,
        public ?float $priceGross = null,
        public int|string|null $tax = null,
        public ?string $quantityUnit = null,
        public ?string $description = null,
        public ?int $categoryId = null,
        public ?string $gtuCode = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'code' => $this->code,
            'price_net' => $this->priceNet,
            'price_gross' => $this->priceGross,
            'tax' => $this->tax,
            'quantity_unit' => $this->quantityUnit,
            'description' => $this->description,
            'category_id' => $this->categoryId,
            'gtu_code' => $this->gtuCode,
        ], static fn ($value): bool => $value !== null);
    }
}
