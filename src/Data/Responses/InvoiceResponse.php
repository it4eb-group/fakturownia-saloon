<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Responses;

use It4eb\Fakturownia\Enums\GovStatus;
use Spatie\LaravelData\Data;

/**
 * A Fakturownia invoice as returned by the API. Keeps the raw payload so
 * callers (e.g. an update reconciler) can read remote positions/recipients.
 */
final class InvoiceResponse extends Data
{
    /**
     * @param  array<int, array<string, mixed>>  $positions
     * @param  array<int, array<string, mixed>>  $recipients
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $number = null,
        public ?string $viewUrl = null,
        public ?string $status = null,
        public ?GovStatus $govStatus = null,
        public ?string $govId = null,
        public ?float $paid = null,
        public array $positions = [],
        public array $recipients = [],
        public array $raw = [],
    ) {}

    /**
     * @param  array<string, mixed>  $json
     */
    public static function fromApi(array $json): self
    {
        return new self(
            id: (int) ($json['id'] ?? 0),
            number: isset($json['number']) ? (string) $json['number'] : null,
            viewUrl: $json['view_url'] ?? null,
            status: $json['status'] ?? null,
            govStatus: GovStatus::tryFromNullable($json['gov_status'] ?? null),
            govId: isset($json['gov_id']) && $json['gov_id'] !== '' ? (string) $json['gov_id'] : null,
            paid: isset($json['paid']) ? (float) $json['paid'] : null,
            positions: array_values((array) ($json['positions'] ?? [])),
            recipients: array_values((array) ($json['recipients'] ?? [])),
            raw: $json,
        );
    }

    /**
     * @return list<int>
     */
    public function positionIds(): array
    {
        return $this->pluckIds($this->positions);
    }

    /**
     * @return list<int>
     */
    public function recipientIds(): array
    {
        return $this->pluckIds($this->recipients);
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @return list<int>
     */
    private function pluckIds(array $rows): array
    {
        $ids = [];

        foreach ($rows as $row) {
            if (isset($row['id'])) {
                $ids[] = (int) $row['id'];
            }
        }

        return $ids;
    }
}
