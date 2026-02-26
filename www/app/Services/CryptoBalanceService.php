<?php

namespace App\Services;

use App\Models\User;
use App\Models\CryptoTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class CryptoBalanceService
{
    /**
     * Зачисление средств (после подтверждения блокчейном)
     */
    public function deposit(User $user, float $amount, string $reference, ?string $txHash = null): CryptoTransaction
    {
        return DB::transaction(function () use ($user, $amount, $reference, $txHash) {

            // Проверка идемпотентности
            if (CryptoTransaction::where('reference', $reference)->exists()) {
                throw new Exception('Deposit already processed');
            }

            // Блокировка строки пользователя
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            // Увеличение баланса
            $user->crypto_balance += $amount;
            $user->save();

            return CryptoTransaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $amount,
                'tx_hash' => $txHash,
                'reference' => $reference,
                'status' => 'confirmed',
            ]);
        });
    }

    /**
     * Списание средств
     */
    public function withdraw(User $user, float $amount): CryptoTransaction
    {
        return DB::transaction(function () use ($user, $amount) {

            $user = User::where('id', $user->id)->lockForUpdate()->first();

            if ($user->crypto_balance < $amount) {
                throw new Exception('Insufficient funds');
            }

            $user->crypto_balance -= $amount;
            $user->save();

            return CryptoTransaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $amount,
                'status' => 'confirmed',
            ]);
        });
    }
}
