<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Enums;

/**
 * KSeF basis for the "np" (not applicable) tax rate. This is an invoice-level
 * field, not a position-level one.
 */
enum NpTaxKind: string
{
    case EXPORT_SERVICE = 'export_service';
    case EXPORT_SERVICE_EU = 'export_service_eu';
    case NOT_SPECIFIED = 'not_specified';
}
