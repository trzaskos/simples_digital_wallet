<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all registered users except the one belonging to the entered ID
     *
     * @param int $userId
     * @return Collection
     */
    public function allUsers(int $userId): Collection
    {
        return User::whereNotIn('id', [$userId])->get();
    }

    /**
     * Search a specific user by ID
     *
     * @param int $userId
     * @return User|null
     */
    public function findUser(int $userId): User|null
    {
        return User::find($userId);
    }

    /**
     * Search a specific user by email
     *
     * @param array $attributes
     * @return User|null
     */
    public function createUser(array $attributes): User|null
    {
        return User::create($attributes);
    }

    /**
     * Search a specific user by document number
     *
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }

    /**
     * Create a new user
     *
     * @param string $docNumber
     * @return User|null
     */
    public function findUserByDocumentNumber(string $docNumber): User|null
    {
        return User::where('document_number', $docNumber)->first();
    }

    /**
     * Generate a token for an user
     *
     * @param User $user
     * @return array
     */
    public function generateToken(User $user): array
    {
        $token = [];
        $token['token'] = $user->createToken($user->name)->plainTextToken;
        $token['name'] = $user->name;

        return $token;
    }
}