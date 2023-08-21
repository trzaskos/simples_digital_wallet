<?php

namespace App\Interfaces;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;

interface WalletRepositoryInterface
{
    /**
     * Create a new wallet for a user
     *
     * @param array $attributes
     * @return Wallet|null
     */
    public function createWallet(array $attributes): Wallet|null;

    /**
     * Get all user wallets
     *
     * @param int $userId
     * @return Collection
     */
    public function getAllWalletsByUser(int $userId): Collection;

    /**
     * Get a specific wallet
     *
     * @param int $walletId
     * @return Wallet|null
     */
    public function getWalletById(int $walletId): Wallet|null;

    /**
     * Get a user's first wallet
     *
     * @param int $userId
     * @return Wallet|null
     */
    public function getFirstWallet(int $userId): Wallet|null;

    /**
     * Update the amount in a wallet
     *
     * @param float $amount
     * @param int $walletId
     * @return bool
     */
    public function updateAmountWallet(float $amount, int $walletId): bool;
}