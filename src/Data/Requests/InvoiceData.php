<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Data\Requests;

use It4eb\Fakturownia\Enums\InvoiceKind;
use It4eb\Fakturownia\Enums\InvoiceStatus;
use It4eb\Fakturownia\Enums\NpTaxKind;
use It4eb\Fakturownia\Enums\PaymentMethod;
use It4eb\Fakturownia\Enums\TaxNoKind;
use It4eb\Fakturownia\Exceptions\InvalidInvoiceDataException;
use Spatie\LaravelData\Data;

/**
 * A Fakturownia invoice payload.
 *
 * The wire format is a flat, prefixed structure (seller_name, buyer_tax_no, ...)
 * with nested positions[]/recipients[]. toApiArray() produces exactly that shape;
 * the typed properties here are the validation/construction boundary.
 */
final class InvoiceData extends Data
{
    /**
     * @param  list<PositionData>  $positions  must not be empty
     * @param  list<RecipientData>  $recipients
     * @param  int|string|null  $paymentDeadline  number of days, or a Y-m-d date, or null
     * @param  list<int|string>  $invoiceIds  related invoice ids (advance -> final, estimate -> advance)
     * @param  list<array{kind: string, content: string, position_index?: int|null}>  $descriptions
     * @param  array<string, mixed>  $other  escape hatch for fields not modelled here
     */
    public function __construct(
        public InvoiceKind $kind,
        public string $issueDate,
        public string $sellDate,
        public PartyData $seller,
        public PartyData $buyer,
        public array $positions,
        public ?string $number = null,
        public ?string $pattern = null,
        public ?int $departmentId = null,
        public ?string $description = null,
        public ?InvoiceStatus $status = null,
        public ?string $place = null,
        public string $lang = 'pl',
        public string $currency = 'PLN',
        public PaymentMethod $paymentMethod = PaymentMethod::TRANSFER,
        public bool $buyerIsCompany = true,
        public ?string $buyerEmail = null,
        public ?string $sellerPerson = null,
        public ?string $buyerPerson = null,
        public ?TaxNoKind $buyerTaxNoKind = null,
        public array $recipients = [],
        public int|string|null $paymentDeadline = null,
        public float $pricePaid = 0.0,
        public ?NpTaxKind $npTaxKind = null,
        public bool $reverseCharge = false,
        public bool $splitPayment = false,
        public bool $govSaveAndSend = false,
        public array $invoiceIds = [],
        public array $descriptions = [],
        public array $other = [],
    ) {
        $this->assertDate($issueDate, 'issueDate');
        $this->assertDate($sellDate, 'sellDate');

        if ($positions === []) {
            throw new InvalidInvoiceDataException('An invoice must have at least one position.');
        }

        foreach ($positions as $position) {
            if (! $position instanceof PositionData) {
                throw new InvalidInvoiceDataException('Every position must be a '.PositionData::class.'.');
            }
        }

        foreach ($recipients as $recipient) {
            if (! $recipient instanceof RecipientData) {
                throw new InvalidInvoiceDataException('Every recipient must be a '.RecipientData::class.'.');
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        $data = [
            'kind' => $this->kind->value,
            'number' => $this->number,
            'pattern' => $this->pattern,
            'department_id' => $this->departmentId,
            'description' => $this->description,
            'status' => $this->status?->value,
            'issue_date' => $this->issueDate,
            'sell_date' => $this->sellDate,
            'payment_type' => $this->paymentMethod->value,
            'place' => $this->place,
            'lang' => $this->lang,
            'currency' => $this->currency,

            'seller_name' => $this->seller->name,
            'seller_tax_no' => $this->seller->taxNo,
            'seller_street' => $this->seller->street,
            'seller_post_code' => $this->seller->postCode,
            'seller_city' => $this->seller->city,
            'seller_country' => $this->seller->country,
            'seller_phone' => $this->seller->phone,
            'seller_person' => $this->sellerPerson ?? $this->seller->person,

            'buyer_name' => $this->buyer->name,
            'buyer_first_name' => $this->buyer->firstName,
            'buyer_last_name' => $this->buyer->lastName,
            'buyer_person' => $this->buyerPerson ?? $this->buyer->person,
            'buyer_tax_no' => $this->buyer->taxNo,
            'buyer_tax_no_kind' => ($this->buyerTaxNoKind ?? $this->buyer->taxNoKind)?->value,
            'buyer_street' => $this->buyer->street,
            'buyer_post_code' => $this->buyer->postCode,
            'buyer_city' => $this->buyer->city,
            'buyer_country' => $this->buyer->country,
            'buyer_phone' => $this->buyer->phone,
            'buyer_company' => $this->buyerIsCompany ? '1' : '0',
            'buyer_email' => $this->buyerEmail ?? $this->buyer->email,

            'split_payment' => $this->splitPayment ? '1' : '0',
        ];

        if ($this->paymentDeadline !== null && $this->paymentDeadline !== '') {
            if (is_numeric($this->paymentDeadline)) {
                $data['payment_to_kind'] = $this->paymentDeadline;
            } else {
                $data['payment_to_kind'] = 'other_date';
                $data['payment_to'] = $this->paymentDeadline;
            }
        }

        if ($this->npTaxKind !== null) {
            $data['np_tax_kind'] = $this->npTaxKind->value;
        }

        if ($this->reverseCharge) {
            $data['reverse_charge'] = true;
        }

        if ($this->govSaveAndSend) {
            $data['gov_save_and_send'] = true;
        }

        if ($this->descriptions !== []) {
            $data['descriptions'] = $this->descriptions;
        }

        if ($this->invoiceIds !== []) {
            $data['invoice_ids'] = $this->invoiceIds;
        }

        if ($this->recipients !== []) {
            $data['recipients'] = array_map(
                static fn (RecipientData $recipient): array => $recipient->toApiArray(),
                $this->recipients,
            );
        }

        $data['positions'] = array_map(
            static fn (PositionData $position): array => $position->toApiArray(),
            $this->positions,
        );

        if ($this->status === InvoiceStatus::PAID || $this->pricePaid > 0) {
            $data['paid'] = $this->pricePaid;
        }

        $data = array_merge($data, $this->other);

        return array_filter($data, static fn ($value): bool => $value !== null);
    }

    private function assertDate(string $value, string $field): void
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) !== 1) {
            throw new InvalidInvoiceDataException("{$field} must be a Y-m-d date, got \"{$value}\".");
        }
    }
}
