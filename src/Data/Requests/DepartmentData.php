<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use It4eb\Fakturownia\Enums\TaxNoKind;
use Spatie\LaravelData\Data;

/**
 * A Fakturownia department — a seller profile (company data + bank account)
 * that an invoice can be issued from.
 */
final class DepartmentData extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $taxNo = null,
        public ?TaxNoKind $taxNoKind = null,
        public ?string $street = null,
        public ?string $postCode = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $bank = null,
        public ?string $bankAccount = null,
        public ?string $bankAccountCurrency = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?string $invoicePattern = null,
        public ?string $shortcut = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'tax_no' => $this->taxNo,
            'tax_no_kind' => $this->taxNoKind?->value,
            'street' => $this->street,
            'post_code' => $this->postCode,
            'city' => $this->city,
            'country' => $this->country,
            'bank' => $this->bank,
            'bank_account' => $this->bankAccount,
            'bank_account_currency' => $this->bankAccountCurrency,
            'phone' => $this->phone,
            'email' => $this->email,
            'invoice_pattern' => $this->invoicePattern,
            'shortcut' => $this->shortcut,
        ], static fn ($value): bool => $value !== null);
    }
}
