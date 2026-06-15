<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Responses;

use Spatie\LaravelData\Data;

final class ClientResponse extends Data
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $taxNo = null,
        public ?string $email = null,
        public ?string $externalId = null,
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
            email: $json['email'] ?? null,
            externalId: isset($json['external_id']) ? (string) $json['external_id'] : null,
            raw: $json,
        );
    }
}
