<?php

declare(strict_types=1);

namespace App\Modules\User\Repositories;

use App\Models\User;
use App\Models\UserProfile;
use App\Modules\User\Repositories\Contracts\ProfileRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function __construct(
        private readonly User $userModel,
        private readonly UserProfile $profileModel
    ) {}

    /**
     * Find or create a profile for a user.
     */
    public function findOrCreateForUser(int $userId): UserProfile
    {
        return $this->profileModel->firstOrCreate(
            ['user_id' => $userId],
            ['user_id' => $userId]
        );
    }

    /**
     * Update user's profile.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProfile(int $userId, array $data): UserProfile
    {
        $profile = $this->findOrCreateForUser($userId);

        $profile->update($data);

        return $profile->fresh();
    }

    /**
     * Update user's basic information.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateUser(int $userId, array $data): User
    {
        $user = $this->userModel->findOrFail($userId);

        $user->update($data);

        return $user->fresh();
    }

    /**
     * Update user's password.
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        $user = $this->userModel->findOrFail($userId);

        return $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
