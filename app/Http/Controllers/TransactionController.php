<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Facades\BillingService;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransactionResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class TransactionController extends Controller
{
    public function __construct(
    ) {
    }

    public function index(Request $request, int $userId)
    {
        $perPage = $request->query('per_page', 10);

        try {
            return TransactionResource::collection(
                BillingService::getUserTransactions($userId, $perPage)
            );
        } catch (ModelNotFoundException) {
            return response()->json([ 'message' => 'user not found' ], status: 404);
        } catch (Exception) {
            return response()->json([ 'message' => 'internal server error' ], status: 500);
        }
    }

    public function deposit(TransactionRequest $request)
    {
        try {
            $roundedAmount = round($request->amount, 2);
            $amount = sprintf('%f', $roundedAmount);

            return new TransactionResource(
                BillingService::makeTransactionOperation(
                    TransactionType::DEPOSIT,
                    $request->user_id,
                    $amount,
                    $request->comment
                )
            );
        } catch (ModelNotFoundException) {
            return response()->json([ 'message' => 'user not found' ], status: 404);
        } catch (Exception|Throwable $exception) {
            \Log::error(__METHOD__ . ' Exception: ' . $exception->getMessage());
            return response()->json([ 'message' => 'internal server error' ], status: 500);
        }
    }

    public function withdraw(TransactionRequest $request)
    {
        try {
            $roundedAmount = round($request->amount, 2);
            $amount = sprintf('%f', $roundedAmount);

            return new TransactionResource(
                BillingService::makeTransactionOperation(
                    TransactionType::WITHDRAW,
                    $request->user_id,
                    $amount,
                    $request->comment
                )
            );
        } catch (\LogicException) {
            return response()->json([ 'message' => 'insufficient funds' ], status: 409);
        } catch (ModelNotFoundException) {
            return response()->json([ 'message' => 'user not found' ], status: 404);
        } catch (Exception|Throwable $exception) {
            \Log::error(__METHOD__ . ' Exception: ' . $exception->getMessage());
            return response()->json([ 'message' => 'internal server error' ], status: 500);
        }
    }

    public function transfer(TransferRequest $request)
    {
        try {
            $roundedAmount = round($request->amount, 2);
            $amount = sprintf('%f', $roundedAmount);

            return new TransactionResource(
                BillingService::transferMoneyBetweenUsers(
                    $request->from_user_id,
                    $request->to_user_id,
                    $amount,
                    $request->comment
                )
            );
        } catch (\LogicException) {
            return response()->json([ 'message' => 'insufficient funds' ], status: 409);
        } catch (ModelNotFoundException) {
            return response()->json([ 'message' => 'user not found' ], status: 404);
        } catch (Exception|Throwable $exception) {
            \Log::error(__METHOD__ . ' Exception: ' . $exception->getMessage());
            return response()->json([ 'message' => 'internal server error' ], status: 500);
        }
    }
}
