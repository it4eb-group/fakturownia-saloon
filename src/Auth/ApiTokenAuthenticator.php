<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Auth;

use Saloon\Contracts\Authenticator;
use Saloon\Http\PendingRequest;

/**
 * Fakturownia expects the api_token either inside the JSON body (POST/PUT
 * write requests that carry a body) or as a query parameter (GET/DELETE and
 * body-less POSTs such as change_status). We detect which by checking whether
 * the request declared a body repository.
 *
 * When added to the body, api_token is a sibling of the "invoice"/"department"
 * wrapper key — never nested inside it.
 */
final class ApiTokenAuthenticator implements Authenticator
{
    public function __construct(private readonly string $token) {}

    public function set(PendingRequest $pendingRequest): void
    {
        $body = $pendingRequest->body();

        if ($body !== null) {
            $body->add('api_token', $this->token);

            return;
        }

        $pendingRequest->query()->add('api_token', $this->token);
    }
}
