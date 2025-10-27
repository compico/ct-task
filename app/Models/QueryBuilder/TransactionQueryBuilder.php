<?php

namespace App\Models\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class TransactionQueryBuilder extends Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    public function whereUserId(int $userId): self
    {
        return $this->where('user_id', $userId);
    }

    public function withParentTransaction(): self
    {
        return $this->with('parent_transaction');
    }
}
