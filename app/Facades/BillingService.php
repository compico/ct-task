<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\BillingService
 */
class BillingService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\BillingService::class;
    }
}
