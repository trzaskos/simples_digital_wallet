<?php

namespace App\Http\Controllers\Api;

use App\Interfaces\WalletRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletApiController extends BaseController
{
    /**
     * @var WalletRepositoryInterface
     */
    private WalletRepositoryInterface $walletRepository;

    /**
     * Class constructor
     *
     * @param WalletRepositoryInterface $walletRepository
     */
    public function __construct(WalletRepositoryInterface $walletRepository) {
        $this->walletRepository = $walletRepository;
    }

    /**
     * Saves a new wallet for a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'user_id' => 'required|integer',
                'amount' => 'required|numeric',
            ]);

            if ($validate->fails()) {
                return $this->sendError('Validation Error.', $validate->errors());
            }

            $newWallet = $this->walletRepository->createWallet($request->all());

            if (!$newWallet) {
                return $this->sendError('An error occurred while creating the wallet');
            }

            return $this->sendSuccess([],'Wallet created');
        } catch (Exception $ex) {
            return $this->sendError('Something is wrong', $ex->getMessage());
        }
    }

    /**
     * Get all user wallets
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getAllByUser(int $userId): JsonResponse
    {
        $wallets = $this->walletRepository->getAllWalletsByUser($userId);
        $totalAmount = $wallets->sum('amount');

        $userWallets = [];
        $userWallets['totalAmount'] = $totalAmount;
        $userWallets['wallets'] = $wallets;

        return $this->sendSuccess($userWallets);
    }

    /**
     * Get a wallet by its id
     *
     * @param int $walletId
     * @return JsonResponse
     */
    public function findById(int $walletId): JsonResponse
    {
        $wallet = $this->walletRepository->getWalletById($walletId);
        return $this->sendSuccess($wallet);
    }
}
