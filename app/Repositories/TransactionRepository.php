<?php

namespace App\Repositories;

use App\Models\QueryBuilder\TransactionQueryBuilder;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository
{
    function getByUserId(int $userId): TransactionQueryBuilder
    {
        return $this->getQuery()->orderByDesc('created_at')->whereUserId($userId)->withParentTransaction();
    }

    /**
     * @return TransactionQueryBuilder
     */
    function getQuery(): Builder
    {
        return Transaction::query();
    }
}
