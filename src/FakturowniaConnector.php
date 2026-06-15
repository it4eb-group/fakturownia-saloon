<?php

declare(strict_types=1);

namespace It4eb\Fakturownia;

use It4eb\Fakturownia\Auth\ApiTokenAuthenticator;
use It4eb\Fakturownia\Resources\CategoryResource;
use It4eb\Fakturownia\Resources\ClientResource;
use It4eb\Fakturownia\Resources\DepartmentResource;
use It4eb\Fakturownia\Resources\InvoiceResource;
use It4eb\Fakturownia\Resources\PaymentResource;
use It4eb\Fakturownia\Resources\ProductResource;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Connector;

/**
 * Connector for the Fakturownia (InvoiceOcean) REST API.
 *
 * Credentials are passed to the constructor, so the same code can serve a
 * global account (from config) or per-tenant accounts without any change.
 */
final class FakturowniaConnector extends Connector
{
    public function __construct(
        public readonly string $domain,
        private readonly string $apiToken,
    ) {}

    public function resolveBaseUrl(): string
    {
        return "https://{$this->domain}.fakturownia.pl";
    }

    public function invoices(): InvoiceResource
    {
        return new InvoiceResource($this);
    }

    public function departments(): DepartmentResource
    {
        return new DepartmentResource($this);
    }

    public function clients(): ClientResource
    {
        return new ClientResource($this);
    }

    public function products(): ProductResource
    {
        return new ProductResource($this);
    }

    public function payments(): PaymentResource
    {
        return new PaymentResource($this);
    }

    public function categories(): CategoryResource
    {
        return new CategoryResource($this);
    }

    /**
     * The api_token is added per-request: into the JSON body for write
     * requests, otherwise as a query parameter. See ApiTokenAuthenticator.
     */
    protected function defaultAuth(): ?Authenticator
    {
        return new ApiTokenAuthenticator($this->apiToken);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }
}
