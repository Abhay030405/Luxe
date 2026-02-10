<?php

declare(strict_types=1);

namespace App\Modules\User\Repositories\Contracts;

use App\Models\User;
use App\Models\UserProfile;

interface ProfileRepositoryInterface
{
    /**
     * Find or create a profile for a user.
     */
    public function findOrCreateForUser(int $userId): UserProfile;

    /**
     * Update user's profile.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProfile(int $userId, array $data): UserProfile;

    /**
     * Update user's basic information.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateUser(int $userId, array $data): User;

    /**
     * Update user's password.
     */
    public function updatePassword(int $userId, string $newPassword): bool;
}
