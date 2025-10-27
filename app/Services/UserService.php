<?php

namespace App\Services;

use App\Enums\CurrencyType;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Money\Currency;
use Money\Money;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function getUserById(int $userId, array $with = []): User
    {
        $user = $this->userRepository->getById($userId, $with);
        if ($user === null) {
            throw new ModelNotFoundException();
        }

        return $user;
    }

    public function makeWalletIfNotExist(User $user): void
    {
        if ($user->wallet === null) {
            $user->wallet()->create([
                'balance' => new Money(0, new Currency(CurrencyType::RUB->value)),
                'currency' => CurrencyType::RUB,
            ]);
        }
    }

    public function getUserWallet(int $userId): Wallet
    {
        $user = $this->userRepository->getById($userId, ['wallet']);
        if ($user === null) {
            throw new ModelNotFoundException();
        }

        if ($user->wallet === null) {
            throw new RelationNotFoundException();
        }

        return $user->wallet;
    }
}
