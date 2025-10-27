<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sum');
            $table->string('currency', 3)->default('RUB');
            $table->enum('type', ['deposit', 'withdraw', 'transfer_in', 'transfer_out'])->default('deposit');
            $table->foreignId('parent_transaction_id')->nullable()->constrained('transactions')->cascadeOnDelete();
            $table->longText('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
