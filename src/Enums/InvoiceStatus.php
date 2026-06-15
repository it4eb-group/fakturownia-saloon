<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Enums;

enum InvoiceStatus: string
{
    case ISSUED = 'issued';
    case SENT = 'sent';
    case PAID = 'paid';
    case PARTIAL = 'partial';
    case REJECTED = 'rejected';
}
