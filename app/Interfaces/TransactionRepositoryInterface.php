<?php

namespace App\Interfaces;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    /**
     * Get a user's transactions
     *
     * @param int $userId
     * @return Collection
     */
    public function getTransactions(int $userId): Collection;

    /**
     * Create a new transaction
     *
     * @param array $attributes
     * @return Transaction|null
     */
    public function createTransaction(array $attributes): Transaction|null;

    /**
     * Get a transaction by its id
     *
     * @param int $transactionId
     * @return Transaction|null
     */
    public function getById(int $transactionId): Transaction|null;

    /**
     * Update a transaction
     *
     * @param string $status
     * @param string $message
     * @param int $transactionId
     * @return bool
     */
    public function updateTransaction(string $status, string $message, int $transactionId): bool;
}