<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('type', ['deposit', 'withdraw']);

            $table->decimal('amount', 20, 8);

            $table->string('tx_hash')->nullable(); // хеш блокчейн транзакции
            $table->string('reference')->nullable(); // idempotency key

            $table->enum('status', ['pending', 'confirmed', 'failed'])
                ->default('pending');

            $table->timestamps();

            $table->unique(['reference']); // защита от повторного зачисления
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_transactions');
    }
};
