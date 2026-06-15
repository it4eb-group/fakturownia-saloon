<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use It4eb\Fakturownia\Enums\TaxNoKind;
use Spatie\LaravelData\Data;

final class ClientData extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $taxNo = null,
        public ?TaxNoKind $taxNoKind = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $street = null,
        public ?string $postCode = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $bank = null,
        public ?string $bankAccount = null,
        public ?bool $company = null,
        public ?string $person = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $externalId = null,
        public ?int $departmentId = null,
        public ?string $note = null,
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
            'email' => $this->email,
            'phone' => $this->phone,
            'street' => $this->street,
            'post_code' => $this->postCode,
            'city' => $this->city,
            'country' => $this->country,
            'bank' => $this->bank,
            'bank_account' => $this->bankAccount,
            'company' => $this->company,
            'person' => $this->person,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'external_id' => $this->externalId,
            'department_id' => $this->departmentId,
            'note' => $this->note,
        ], static fn ($value): bool => $value !== null);
    }
}
