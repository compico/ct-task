<?php

namespace App\Models\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class WalletQueryBuilder extends Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    public function whereUserId(int $userId): self
    {
        return $this->where('user_id', $userId);
    }
}
