<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\CurrencyType;
use App\Models\QueryBuilder\WalletQueryBuilder;
use Database\Factories\WalletFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Money\Money;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read Money $balance
 * @property-read CurrencyType $currency
 * @property-read User $user
 * @property-read Transaction[] $transaction
 */
class Wallet extends Model
{
    /** @use HasFactory<WalletFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'balance' => MoneyCast::class.':balance,currency',
            'currency' => CurrencyType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @param Builder $query
     * @return WalletQueryBuilder
     */
    public function newEloquentBuilder($query): WalletQueryBuilder
    {
        return new WalletQueryBuilder($query);
    }

    public function getId(): int
    {
        return $this->getAttributeFromArray('id');
    }

    public function getUserId(): int
    {
        return $this->getAttributeFromArray('user_id');
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function setBalance(Money $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCurrency(): CurrencyType
    {
        return $this->currency;
    }
}
