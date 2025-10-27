<?php

namespace App\Http\Controllers;

use App\Facades\BillingService;
use App\Facades\UserService;
use App\Http\Resources\WalletResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class WalletController extends Controller
{
    public function show(int $userId)
    {
        try {
            return new WalletResource(UserService::getUserWallet($userId));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message'  => 'user not  found',
            ], status: 404);
        } catch (RelationNotFoundException $e) {
            return response()->json([
                'message'  => 'user have no wallet',
            ], status: 404);
        }
    }
}
