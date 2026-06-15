<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use It4eb\Fakturownia\Exceptions\InvalidInvoiceDataException;
use Spatie\LaravelData\Data;

/**
 * An invoice line item (Fakturownia "positions[]").
 *
 * Prices follow Fakturownia's convention: total_price_gross is the gross value
 * for the whole line (unit gross * quantity, after discount). The remote id and
 * destroy flag support update reconciliation.
 */
final class PositionData extends Data
{
    /**
     * @param  int|string  $tax  numeric VAT percent (e.g. 23) or a symbol ("zw", "np")
     */
    public function __construct(
        public string $name,
        public float $quantity,
        public float $totalPriceGross,
        public int|string $tax = 23,
        public ?string $quantityUnit = null,
        public ?string $code = null,
        public ?string $gtuCode = null,
        public ?string $description = null,
        public ?int $id = null,
        public bool $destroy = false,
    ) {
        if (trim($name) === '') {
            throw new InvalidInvoiceDataException('Position name must not be empty.');
        }

        if ($quantity <= 0) {
            throw new InvalidInvoiceDataException("Position \"{$name}\" must have a positive quantity.");
        }

        if ($totalPriceGross < 0) {
            throw new InvalidInvoiceDataException("Position \"{$name}\" must not have a negative gross price.");
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        $data = [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'quantity_unit' => $this->quantityUnit,
            'tax' => $this->tax,
            'total_price_gross' => $this->totalPriceGross,
            'code' => $this->code,
            'gtu_code' => $this->gtuCode,
            'description' => $this->description,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        if ($this->destroy) {
            $data['_destroy'] = 1;
        }

        return array_filter($data, static fn ($value) => $value !== null);
    }
}
