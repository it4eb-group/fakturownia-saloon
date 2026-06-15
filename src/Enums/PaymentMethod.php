<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Enums;

enum PaymentMethod: string
{
    case TRANSFER = 'transfer';
    case CARD = 'card';
    case CASH = 'cash';
    case BARTER = 'barter';
    case CHEQUE = 'cheque';
    case BILL_OF_EXCHANGE = 'bill_of_exchange';
    case CASH_ON_DELIVERY = 'cash_on_delivery';
    case COMPENSATION = 'compensation';
    case LETTER_OF_CREDIT = 'letter_of_credit';
    case PAYU = 'payu';
    case PAYPAL = 'paypal';
    case OFF = 'off';
}
