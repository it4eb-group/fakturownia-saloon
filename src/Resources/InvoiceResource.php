<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Resources;

use It4eb\Fakturownia\Data\Requests\InvoiceData;
use It4eb\Fakturownia\Data\Responses\InvoiceResponse;
use It4eb\Fakturownia\Enums\InvoiceStatus;
use It4eb\Fakturownia\Requests\Invoices\ChangeInvoiceStatus;
use It4eb\Fakturownia\Requests\Invoices\CreateInvoice;
use It4eb\Fakturownia\Requests\Invoices\DeleteInvoice;
use It4eb\Fakturownia\Requests\Invoices\GetInvoice;
use It4eb\Fakturownia\Requests\Invoices\GetInvoiceKsefUpo;
use It4eb\Fakturownia\Requests\Invoices\GetInvoiceKsefXml;
use It4eb\Fakturownia\Requests\Invoices\GetInvoicePdf;
use It4eb\Fakturownia\Requests\Invoices\ListInvoices;
use It4eb\Fakturownia\Requests\Invoices\SendInvoiceByEmail;
use It4eb\Fakturownia\Requests\Invoices\SendInvoiceToKsef;
use It4eb\Fakturownia\Requests\Invoices\UpdateInvoice;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

final class InvoiceResource extends BaseResource
{
    public function create(InvoiceData $invoice): InvoiceResponse
    {
        return $this->connector->send(new CreateInvoice($invoice))->dtoOrFail();
    }

    /**
     * @param  InvoiceData|array<string, mixed>  $invoice  full invoice or a partial patch
     */
    public function update(int $id, InvoiceData|array $invoice): InvoiceResponse
    {
        $payload = $invoice instanceof InvoiceData ? $invoice->toApiArray() : $invoice;

        return $this->connector->send(new UpdateInvoice($id, $payload))->dtoOrFail();
    }

    public function get(int $id, bool $includePositions = false): InvoiceResponse
    {
        return $this->connector->send(new GetInvoice($id, $includePositions))->dtoOrFail();
    }

    /**
     * Non-throwing existence check (does not raise on a 404).
     */
    public function exists(int $id): bool
    {
        return $this->connector->send(new GetInvoice($id))->successful();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return list<InvoiceResponse>
     */
    public function list(array $filters = []): array
    {
        return $this->connector->send(new ListInvoices($filters))->dtoOrFail();
    }

    public function delete(int $id): Response
    {
        return $this->connector->send(new DeleteInvoice($id));
    }

    public function changeStatus(int $id, InvoiceStatus $status): Response
    {
        return $this->connector->send(new ChangeInvoiceStatus($id, $status));
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function sendByEmail(int $id, array $options = []): bool
    {
        return $this->connector->send(new SendInvoiceByEmail($id, $options))->successful();
    }

    /**
     * Raw PDF bytes.
     */
    public function pdf(int $id): string
    {
        return $this->connector->send(new GetInvoicePdf($id))->body();
    }

    public function sendToKsef(int $id): InvoiceResponse
    {
        return $this->connector->send(new SendInvoiceToKsef($id))->dtoOrFail();
    }

    /**
     * Raw KSeF (FA) XML.
     */
    public function ksefXml(int $id): string
    {
        return $this->connector->send(new GetInvoiceKsefXml($id))->body();
    }

    /**
     * Raw KSeF UPO XML.
     */
    public function ksefUpo(int $id): string
    {
        return $this->connector->send(new GetInvoiceKsefUpo($id))->body();
    }
}
