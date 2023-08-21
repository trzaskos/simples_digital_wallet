<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * Get a user's transactions
     *
     * @param int $userId
     * @return Collection
     */
    public function getTransactions(int $userId): Collection
    {
        return Transaction::where(function($query) use ($userId) {
            $query->where('payer', $userId)->where('type', 'SEND');
        })->orWhere(function($query) use ($userId) {
            $query->where('payee', $userId)->where('type', 'RECEIVE');
        })->get();
    }

    /**
     * Create a new transaction
     *
     * @param array $attributes
     * @return Transaction|null
     */
    public function createTransaction(array $attributes): Transaction|null
    {
        return Transaction::create($attributes);
    }

    /**
     * Get a transaction by its id
     *
     * @param int $transactionId
     * @return Transaction|null
     */
    public function getById(int $transactionId): Transaction|null
    {
        return Transaction::find($transactionId);
    }

    /**
     * Update a transaction
     *
     * @param string $status
     * @param string $message
     * @param int $transactionId
     * @return bool
     */
    public function updateTransaction(string $status, string $message, int $transactionId): bool
    {
        $transaction = Transaction::find($transactionId);

        if (is_null($transaction)) {
            return false;
        }

        $transaction['status'] = $status;
        $transaction['status_message'] = $message;
        return $transaction->save();
    }
}