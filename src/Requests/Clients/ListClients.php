<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Clients;

use It4eb\Fakturownia\Data\Responses\ClientResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class ListClients extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $filters  e.g. ['tax_no' => '...', 'external_id' => '...', 'page' => 1]
     */
    public function __construct(private readonly array $filters = []) {}

    public function resolveEndpoint(): string
    {
        return '/clients.json';
    }

    /**
     * @return list<ClientResponse>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $client): ClientResponse => ClientResponse::fromApi($client),
            $response->json(),
        );
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
