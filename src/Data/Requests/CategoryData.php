<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use Spatie\LaravelData\Data;

final class CategoryData extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'description' => $this->description,
        ], static fn ($value): bool => $value !== null);
    }
}
