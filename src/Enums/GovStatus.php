<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Enums;

/**
 * KSeF submission status reported by Fakturownia on the invoice (gov_status).
 */
enum GovStatus: string
{
    case NOT_SENT = 'not_sent';
    case PROCESSING = 'processing';
    case SENT = 'sent';
    case OK = 'ok';
    case SEND_ERROR = 'send_error';
    case ERROR = 'error';

    public static function tryFromNullable(?string $value): ?self
    {
        return $value === null || $value === '' ? null : self::tryFrom($value);
    }
}
