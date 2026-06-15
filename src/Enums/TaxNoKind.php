<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Enums;

/**
 * KSeF buyer_tax_no_kind: distinguishes a Polish NIP from an EU VAT number
 * or another foreign identifier.
 */
enum TaxNoKind: string
{
    case NIP = '';
    case NIP_UE = 'nip_ue';
    case OTHER = 'other';
}
