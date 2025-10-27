<?php

namespace App\Models\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class UserQueryBuilder extends Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    public function whereId(int $id): self
    {
        return $this->where('id', $id);
    }
}
