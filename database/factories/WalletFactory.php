<?php

namespace Database\Factories;

use App\Enums\CurrencyType;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Money\Currency;
use Money\Money;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'balance' => new Money(fake()->randomNumber(7), new Currency('RUB')),
            'currency' => CurrencyType::RUB,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
