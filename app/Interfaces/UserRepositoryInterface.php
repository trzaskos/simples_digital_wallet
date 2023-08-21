<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Get all registered users except the one belonging to the entered ID
     *
     * @param int $userId
     * @return Collection
     */
    public function allUsers(int $userId): Collection;

    /**
     * Search a specific user by ID
     *
     * @param int $userId
     * @return User|null
     */
    public function findUser(int $userId): User|null;

    /**
     * Search a specific user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email): User|null;

    /**
     * Search a specific user by document number
     *
     * @param string $docNumber
     * @return User|null
     */
    public function findUserByDocumentNumber(string $docNumber): User|null;

    /**
     * Create a new user
     *
     * @param array $attributes
     * @return User|null
     */
    public function createUser(array $attributes): User|null;

    /**
     * Generate a token for an user
     *
     * @param User $user
     * @return array
     */
    public function generateToken(User $user): array;
}