<?php

namespace App\Services;

use App\Enums\CurrencyType;
use App\Enums\TransactionType;
use App\Facades\UserService;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;
use LogicException;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Throwable;

class BillingService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    public function getUserTransactions(int $userId, int $perPage = 10): Paginator
    {
        $user = $this->userRepository->getById($userId);
        if ($user === null) {
            throw new ModelNotFoundException();
        }

        $user_transactions = $this->transactionRepository->getByUserId($userId);

        return $user_transactions->simplePaginate($perPage);
    }

    /**
     * @throws Throwable
     */
    public function addMoneyToWallet(
        TransactionType $type,
        int $userId,
        string $amount,
        string $comment,
        ?Transaction $parent_transaction = null
    ): Transaction {
        $user = UserService::getUserById($userId, ['wallet']);

        $money = $this->parseMoneyFromString($amount);

        UserService::makeWalletIfNotExist($user);

        $transaction = $this->makeTransaction(
            $user,
            $type,
            $money,
            $comment,
            $parent_transaction,
        );

        $user->wallet->setBalance($user->wallet->balance->add($money));
        $user->wallet->save();

        return $transaction;
    }

    /**
     * @throws Throwable
     */
    public function subMoneyFromWallet(TransactionType $type, int $userId, string $amount, string $comment): Transaction
    {
        $user = UserService::getUserById($userId, ['wallet']);

        $money = $this->parseMoneyFromString($amount);

        UserService::makeWalletIfNotExist($user);

        $transaction = $this->makeTransaction($user, $type, $money, $comment);

        $newValue = $user->wallet->balance->subtract($money);
        if ($newValue->isNegative()) {
            throw new LogicException();
        }

        $user->wallet->setBalance($newValue);
        $user->wallet->save();

        return $transaction;
    }

    /**
     * @throws Throwable
     */
    public function makeTransactionOperation(
        TransactionType $type,
        int $userId,
        string $amount,
        string $comment
    ): Transaction {
        return DB::transaction(function () use ($type, $userId, $amount, $comment) {
            return match ($type) {
                TransactionType::DEPOSIT => $this->addMoneyToWallet(
                    $type,
                    $userId,
                    $amount,
                    $comment
                ),
                TransactionType::WITHDRAW => $this->subMoneyFromWallet(
                    $type,
                    $userId,
                    $amount,
                    $comment
                ),
                default => throw new LogicException('Unknown transaction type'),
            };
        });
    }

    /**
     * @throws Throwable
     */
    public function transferMoneyBetweenUsers(
        int $from_user_id,
        int $to_user_id,
        string $amount,
        string $comment
    ): Transaction {
        return DB::transaction(function () use ($from_user_id, $to_user_id, $amount, $comment) {
            $parentTransaction = $this->subMoneyFromWallet(
                TransactionType::TRANSFER_OUT,
                $from_user_id,
                $amount,
                $comment
            );

            return $this->addMoneyToWallet(
                TransactionType::TRANSFER_IN,
                $to_user_id,
                $amount,
                $comment,
                $parentTransaction,
            );
        });
    }

    protected function makeTransaction(
        User $user,
        TransactionType $type,
        Money $money,
        string $comment,
        ?Transaction $parent_transaction = null,
    ): Transaction {
        $transaction = new Transaction();
        $transaction->sum = $money;
        $transaction->comment = $comment;
        $transaction->type = $type;
        $transaction->currency = CurrencyType::RUB;
        $transaction->user()->associate($user);
        if ($parent_transaction !== null) {
            $transaction->parent_transaction()->associate($parent_transaction);
        }

        $transaction->save();

        return $transaction;
    }

    protected function parseMoneyFromString(string $amount): Money
    {
        $moneyParse = new DecimalMoneyParser(new ISOCurrencies());

        return $moneyParse->parse(
            $amount,
            new Currency(CurrencyType::RUB->value)
        );
    }
}
