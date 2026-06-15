<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use Spatie\LaravelData\Data;

/**
 * An additional invoice recipient (Fakturownia "recipients[]"). Carries the
 * remote id and a destroy flag so updates can reconcile recipients that were
 * removed locally but still exist remotely.
 */
final class RecipientData extends Data
{
    public function __construct(
        public ?string $name = null,
        public bool $company = false,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $street = null,
        public ?string $postCode = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $phone = null,
        public ?int $id = null,
        public bool $destroy = false,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        $data = [
            'company' => $this->company,
            'name' => $this->name ?? '',
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'street' => $this->street,
            'post_code' => $this->postCode,
            'city' => $this->city,
            'country' => $this->country,
            'phone' => $this->phone,
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
