<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Clients;

use It4eb\Fakturownia\Data\Responses\ClientResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetClient extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/clients/{$this->id}.json";
    }

    public function createDtoFromResponse(Response $response): ClientResponse
    {
        return ClientResponse::fromApi($response->json());
    }
}
