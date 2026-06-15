<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Clients;

use It4eb\Fakturownia\Data\Responses\ClientResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class UpdateClient extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $client
     */
    public function __construct(
        private readonly int $id,
        private readonly array $client,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/clients/{$this->id}.json";
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
