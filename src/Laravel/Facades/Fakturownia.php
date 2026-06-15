<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use It4eb\Fakturownia\FakturowniaConnector;

/**
 * @method static \It4eb\Fakturownia\Resources\InvoiceResource invoices()
 * @method static \It4eb\Fakturownia\Resources\DepartmentResource departments()
 * @method static \It4eb\Fakturownia\Resources\ClientResource clients()
 * @method static \It4eb\Fakturownia\Resources\ProductResource products()
 * @method static \It4eb\Fakturownia\Resources\PaymentResource payments()
 * @method static \It4eb\Fakturownia\Resources\CategoryResource categories()
 *
 * @see FakturowniaConnector
 */
final class Fakturownia extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FakturowniaConnector::class;
    }
}
