<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use It4eb\Fakturownia\Enums\TaxNoKind;
use Spatie\LaravelData\Data;

/**
 * A seller or buyer on an invoice. Fakturownia serialises these as flat,
 * prefixed keys (seller_name, buyer_tax_no, ...), which is handled by
 * InvoiceData::toApiArray() rather than here.
 */
final class PartyData extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $taxNo = null,
        public ?string $street = null,
        public ?string $postCode = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $phone = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $person = null,
        public ?string $email = null,
        public ?TaxNoKind $taxNoKind = null,
    ) {}
}
