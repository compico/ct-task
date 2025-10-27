<?php

namespace App\Repositories;

use App\Models\QueryBuilder\WalletQueryBuilder;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Money\Currency;
use Money\Money;

class WalletRepository
{
    /**
     * @return WalletQueryBuilder
     */
    public function getQuery(): Builder
    {
        return Wallet::query();
    }
}
