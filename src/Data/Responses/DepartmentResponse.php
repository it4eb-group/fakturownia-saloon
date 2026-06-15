<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Responses;

use Spatie\LaravelData\Data;

final class DepartmentResponse extends Data
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $taxNo = null,
        public ?string $bank = null,
        public ?string $bankAccount = null,
        public ?string $bankAccountCurrency = null,
        public ?string $street = null,
        public ?string $postCode = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $invoicePattern = null,
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
            taxNo: $json['tax_no'] ?? null,
            bank: $json['bank'] ?? null,
            bankAccount: $json['bank_account'] ?? null,
            bankAccountCurrency: $json['bank_account_currency'] ?? null,
            street: $json['street'] ?? null,
            postCode: $json['post_code'] ?? null,
            city: $json['city'] ?? null,
            country: $json['country'] ?? null,
            invoicePattern: $json['invoice_pattern'] ?? null,
            raw: $json,
        );
    }
}
