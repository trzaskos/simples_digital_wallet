<?php

namespace App\Http\Controllers;

use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\WalletRepositoryInterface;
use App\Services\WalletApi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private WalletRepositoryInterface $walletRepo;
    private TransactionRepositoryInterface $transactionRepo;

    public function __construct(WalletRepositoryInterface $walletRepo,
                                TransactionRepositoryInterface $transactionRepo) {
        $this->walletRepo = $walletRepo;
        $this->transactionRepo = $transactionRepo;
    }

    public function index() {
        $wallets = $this->walletRepo->getAllWalletsByUser(Auth::id());
        $transactions = $this->transactionRepo->getTransactions(Auth::id());
        $totalBalance = $wallets->sum('amount');

        return view('dashboard', compact('wallets', 'transactions', 'totalBalance'));
    }
}
