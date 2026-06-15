<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Enums;

/**
 * Fakturownia invoice "kind" values.
 *
 * @see https://app.fakturownia.pl/api
 */
enum InvoiceKind: string
{
    case VAT = 'vat';
    case PROFORMA = 'proforma';
    case BILL = 'bill';
    case RECEIPT = 'receipt';
    case ADVANCE = 'advance';
    case FINAL = 'final';
    case CORRECTION = 'correction';
    case ESTIMATE = 'estimate';
    case VAT_MARGIN = 'vat_margin';
    case KP = 'kp';
    case KW = 'kw';
    case CLIENT_ORDER = 'client_order';
    case WNT = 'wnt';
    case WDT = 'wdt';
    case EXPORT_PRODUCTS = 'export_products';
    case IMPORT_SERVICE = 'import_service';
    case IMPORT_SERVICE_EU = 'import_service_eu';
}
