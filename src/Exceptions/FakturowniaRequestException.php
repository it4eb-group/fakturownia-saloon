<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Exceptions;

use Saloon\Http\Response;

/**
 * Thrown for failed Fakturownia API responses (4xx/5xx). Exposes the parsed
 * error message(s) returned by the API when available.
 */
class FakturowniaRequestException extends FakturowniaException
{
    public function __construct(
        public readonly Response $response,
        string $message = '',
    ) {
        parent::__construct(
            $message !== '' ? $message : self::extractMessage($response),
            $response->status(),
        );
    }

    public function status(): int
    {
        return $this->response->status();
    }

    /**
     * Fakturownia is inconsistent about error shapes. Try the common ones:
     * {"message": "..."} | {"code": "...", "message": {...}} | {"errors": {...}}.
     */
    private static function extractMessage(Response $response): string
    {
        $json = $response->json();

        if (is_array($json)) {
            foreach (['message', 'error', 'errors'] as $key) {
                if (isset($json[$key]) && $json[$key] !== '') {
                    return is_string($json[$key])
                        ? $json[$key]
                        : (string) json_encode($json[$key], JSON_UNESCAPED_UNICODE);
                }
            }
        }

        return "Fakturownia request failed with status {$response->status()}.";
    }
}
