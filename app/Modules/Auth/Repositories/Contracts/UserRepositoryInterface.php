<?php

declare(strict_types=1);

namespace App\Modules\Auth\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create a new user.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User;

    /**
     * Check if email already exists.
     */
    public function emailExists(string $email): bool;
}
