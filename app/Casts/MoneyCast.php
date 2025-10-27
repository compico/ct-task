<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;
use Money\Money;

class MoneyCast implements CastsAttributes
{
    protected string $amount;
    protected string $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): Money
    {
        return new Money(
            $attributes[$this->amount],
            new Currency($attributes[$this->currency]),
        );
    }

    /**
     * @param Model $model
     * @param string $key
     * @param Money $value
     * @param array $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            $this->amount => (int) $value->getAmount(),
            $this->currency => $value->getCurrency()->getCode(),
        ];
    }
}
