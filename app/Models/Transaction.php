<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\CurrencyType;
use App\Enums\TransactionType;
use App\Models\QueryBuilder\TransactionQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Query\Builder;
use Money\Money;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property Money $sum
 * @property CurrencyType $currency
 * @property TransactionType $type
 * @property-read int $parent_transaction_id
 * @property string $comment
 * @property-read Transaction $parent_transaction
 * @property-read User $user
 */
class Transaction extends Model
{
    protected $casts = [
        'sum' => MoneyCast::class . ':sum,currency',
        'type' => TransactionType::class,
        'currency' => CurrencyType::class,
    ];

    protected $guarded = [
        'id',
        'user_id',
        'parent_transaction_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent_transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'parent_transaction_id');
    }

    public function wallet(): HasOneThrough
    {
        return $this->hasOneThrough(Wallet::class, User::class, 'user_id', 'id');
    }

    /**
     * @param Builder $query
     * @return TransactionQueryBuilder
     */
    public function newEloquentBuilder($query): TransactionQueryBuilder
    {
        return new TransactionQueryBuilder($query);
    }

    function getId(): int
    {
        return $this->getAttributeFromArray('id');
    }

    function getUserId(): int
    {
        return $this->getAttributeFromArray('user_id');
    }

    function getSum(): Money
    {
        return $this->sum;
    }

    function getCurrency(): CurrencyType
    {
        return $this->currency;
    }

    function getType(): TransactionType
    {
        return $this->type;
    }

    function getParentTransactionId(): int
    {
        return $this->getAttributeFromArray('parent_transaction_id');
    }

    function getComment(): string
    {
        return $this->getAttributeFromArray('comment');
    }

    function getParentTransaction(): Transaction
    {
        return $this->parent_transaction;
    }

    function getUser(): User
    {
        return $this->user;
    }
}
