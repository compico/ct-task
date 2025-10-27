<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/api/{user_id}/transactions', [TransactionController::class, 'index']);
Route::post('/api/deposit', [TransactionController::class, 'deposit']);
Route::post('/api/withdraw', [TransactionController::class, 'withdraw']);
Route::post('/api/transfer', [TransactionController::class, 'transfer']);
Route::get('/api/balance/{user_id}', [WalletController::class, 'show'] );
