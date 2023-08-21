<?php

namespace App\Http\Controllers\Api;

use App\Helpers\EndPoint;
use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserApiController extends BaseController
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * Class constructor
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users except logged in
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function index(int $userId): JsonResponse
    {
        $users = $this->userRepository->allUsers($userId);

        if (empty($users)) {
            return $this->sendError('Users Not Found');
        }

        return $this->sendSuccess($users);
    }

    /**
     * Save a new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|unique:users|max:255',
                'document_number' => 'required|unique:users|max:255',
                'document_type' => 'required|in:CPF,CNPJ',
                'password' => 'required|max:255',
                'role' => 'required|in:COMMON_USER,SHOPKEEPER',
            ]);

            if ($validate->fails()) {
                return $this->sendError('Validation Error.', $validate->errors());
            }

            $newUser = $this->userRepository->createUser($request->all());

            if (!$newUser) {
                return $this->sendError('An error occurred while creating the user');
            }

            $token = $this->userRepository->generateToken($newUser);

            return $this->sendSuccess($token, 'User created');
        } catch (Exception $ex) {
            return $this->sendError('Something is wrong', $ex->getMessage());
        }
    }
}
