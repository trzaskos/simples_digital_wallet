<?php

namespace App\Repositories;

use App\Interfaces\WalletRepositoryInterface;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;

class WalletRepository implements WalletRepositoryInterface
{
    /**
     * Create a new wallet for a user
     *
     * @param array $attributes
     * @return Wallet|null
     */
    public function createWallet(array $attributes): Wallet|null
    {
        return Wallet::create($attributes);
    }

    /**
     * Get all user wallets
     *
     * @param int $userId
     * @return Collection
     */
    public function getAllWalletsByUser(int $userId): Collection
    {
        return Wallet::where('user_id', $userId)->get();
    }

    /**
     * Get a specific wallet
     *
     * @param int $walletId
     * @return Wallet|null
     */
    public function getWalletById(int $walletId): Wallet|null
    {
        return Wallet::find($walletId);
    }

    /**
     * Get a user's first wallet
     *
     * @param int $userId
     * @return Wallet|null
     */
    public function getFirstWallet(int $userId): Wallet|null
    {
        return Wallet::where('user_id', $userId)->first();
    }

    /**
     * Update the amount in a wallet
     *
     * @param float $amount
     * @param int $walletId
     * @return bool
     */
    public function updateAmountWallet(float $amount, int $walletId): bool
    {
        $wallet = Wallet::find($walletId);
        $wallet->amount = $amount;
        return $wallet->save();
    }
}