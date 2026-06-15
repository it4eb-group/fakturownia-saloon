<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Resources;

use It4eb\Fakturownia\Data\Requests\ClientData;
use It4eb\Fakturownia\Data\Responses\ClientResponse;
use It4eb\Fakturownia\Requests\Clients\CreateClient;
use It4eb\Fakturownia\Requests\Clients\DeleteClient;
use It4eb\Fakturownia\Requests\Clients\GetClient;
use It4eb\Fakturownia\Requests\Clients\ListClients;
use It4eb\Fakturownia\Requests\Clients\UpdateClient;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

final class ClientResource extends BaseResource
{
    /**
     * @param  array<string, mixed>  $filters
     * @return list<ClientResponse>
     */
    public function list(array $filters = []): array
    {
        return $this->connector->send(new ListClients($filters))->dtoOrFail();
    }

    public function get(int $id): ClientResponse
    {
        return $this->connector->send(new GetClient($id))->dtoOrFail();
    }

    /**
     * @param  ClientData|array<string, mixed>  $client
     */
    public function create(ClientData|array $client): ClientResponse
    {
        $payload = $client instanceof ClientData ? $client->toApiArray() : $client;

        return $this->connector->send(new CreateClient($payload))->dtoOrFail();
    }

    /**
     * @param  ClientData|array<string, mixed>  $client
     */
    public function update(int $id, ClientData|array $client): ClientResponse
    {
        $payload = $client instanceof ClientData ? $client->toApiArray() : $client;

        return $this->connector->send(new UpdateClient($id, $payload))->dtoOrFail();
    }

    public function delete(int $id): Response
    {
        return $this->connector->send(new DeleteClient($id));
    }
}
