<?php

namespace App\Repositories;

use App\Models\QueryBuilder\UserQueryBuilder;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function getById(int $id, array $with = []): ?User
    {
        $query = $this->getQuery()->whereId($id);
        if (!empty($with)) {
            $query->with($with);
        }

        return $query->first();
    }

    /**
     * @return UserQueryBuilder
     */
    public function getQuery(): Builder
    {
        return User::query();
    }
}
