<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Clients;

use It4eb\Fakturownia\Data\Responses\ClientResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateClient extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $client
     */
    public function __construct(private readonly array $client) {}

    public function resolveEndpoint(): string
    {
        return '/clients.json';
    }

    public function createDtoFromResponse(Response $response): ClientResponse
    {
        return ClientResponse::fromApi($response->json());
    }

    protected function defaultBody(): array
    {
        return ['client' => $this->client];
    }
}
