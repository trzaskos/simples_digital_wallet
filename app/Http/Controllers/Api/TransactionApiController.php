<?php

namespace App\Http\Controllers\Api;

use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\WalletRepositoryInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionApiController extends BaseController
{
    /**
     * @var TransactionRepositoryInterface
     */
    private TransactionRepositoryInterface $transactionRepo;

    /**
     * @var WalletRepositoryInterface
     */
    private WalletRepositoryInterface $walletRepo;

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepo;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * Class constructor
     *
     * @param TransactionRepositoryInterface $transactionRepo
     * @param WalletRepositoryInterface $walletRepo
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct(TransactionRepositoryInterface $transactionRepo,
                                WalletRepositoryInterface      $walletRepo,
                                UserRepositoryInterface        $userRepo) {
        $this->transactionRepo = $transactionRepo;
        $this->walletRepo = $walletRepo;
        $this->userRepo = $userRepo;
        $this->client = new Client();
    }

    /**
     * Get transactions by user id
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getTransactionsByUser(int $userId): JsonResponse
    {
        $transaction = $this->transactionRepo->getTransactions($userId);
        return $this->sendSuccess(['data' => $transaction]);
    }

    /**
     * Send money
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMoney(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'payer' => 'required|integer',
            'payee' => 'required|integer',
            'wallet_id' => 'required|integer',
            'amount' => 'required|numeric',
            'description' => 'nullable|max:255',
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validation Error.', $validate->errors());
        }

        $sender = $this->userRepo->findUser($request['payer']);

        if ($sender->role == 'SHOPKEEPER') {
            return $this->sendError('Operation not allowed for shopkeepers.');
        }

        $recipientWallet = $this->walletRepo->getFirstWallet($request['payee']);
        $senderWallet = $this->walletRepo->getWalletById($request['wallet_id']);
        $transactionSender = $request->all();
        $transactionSender['type'] = 'SEND';

        if (empty($recipientWallet) || $senderWallet->amount < $request['amount']) {
            $transactionSender['status'] = 'FAIL';
            $transactionSender['status_message'] = 'Insufficient funds';
            $this->transactionRepo->createTransaction($transactionSender);

            return $this->sendError('Insufficient funds');
        }

        $serviceAvailability = $this->checkServiceIsAvailable();

        if (!$serviceAvailability['success'] || $serviceAvailability['body']['message'] != 'Autorizado') {
            return $this->sendError('Unavailable service');
        }

        DB::beginTransaction();
        try {
            $transactionSender['status'] = 'SUCCESS';
            $transactionSender['status_message'] = 'Successfully completed transaction';
            $this->transactionRepo->createTransaction($transactionSender);

            $recipientTransaction = $request->all();
            $recipientTransaction['type'] = 'RECEIVE';
            $recipientTransaction['status'] = 'SUCCESS';
            $recipientTransaction['status_message'] = 'Transaction received';
            $recipientTransaction['wallet_id'] = $recipientWallet->id;
            $this->transactionRepo->createTransaction($recipientTransaction);

            $senderNewAmount = floatval($senderWallet->amount - $request['amount']);
            $recipientNewAmount = floatval($recipientWallet->amount + $request['amount']);
            $this->walletRepo->updateAmountWallet($senderNewAmount, $senderWallet->id);
            $this->walletRepo->updateAmountWallet($recipientNewAmount, $recipientWallet->id);
            DB::commit();

            $serviceNotifyAvailable = $this->checkNotifyServiceAvailable();
            $messageResponse = !$serviceNotifyAvailable['success'] || !$serviceNotifyAvailable['available'] ? 'Notification push service unavailable' : 'Notification sent';

            return $this->sendSuccess([
                'notification' => $messageResponse
            ], 'Successfully completed transaction');
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->sendError('Something is wrong. Rollback was activate.', $ex->getMessage());
        }
    }

    /**
     * Check if transact service is available
     *
     * @return array
     */
    private function checkServiceIsAvailable(): array
    {
        try {
            $uri = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';
            $request = $this->client->get($uri);
            $body = $request->getBody();
            $statusCode = $request->getStatusCode();

            return [
                'success' => $statusCode == 200,
                'body' => $body
            ];
        } catch (GuzzleException $ex) {
            return [
                'success' => false,
                'errors' => $ex->getMessage()
            ];
        }
    }

    /**
     * Check if notify service is available
     *
     * @return array
     */
    private function checkNotifyServiceAvailable(): array
    {
        try {
            $uri = 'https://my.api.mockaroo.com/notify.json?key=8996d3a0';
            $request = $this->client->get($uri);
            $body = $request->getBody();
            $statusCode = $request->getStatusCode();

            return [
                'success' => $statusCode == 200,
                'body' => $body
            ];
        } catch (GuzzleException $ex) {
            return [
                'success' => false,
                'errors' => $ex->getMessage()
            ];
        }
    }
}
