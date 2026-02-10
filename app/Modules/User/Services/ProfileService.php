<?php

declare(strict_types=1);

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\DTOs\ProfileDTO;
use App\Modules\User\Repositories\Contracts\ProfileRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function __construct(
        private readonly ProfileRepositoryInterface $profileRepository
    ) {}

    /**
     * Get authenticated user's profile.
     */
    public function getAuthenticatedUserProfile(): ProfileDTO
    {
        /** @var User $user */
        $user = Auth::user();

        // Eager load profile to avoid N+1
        $user->load('profile');

        return ProfileDTO::fromModel($user);
    }

    /**
     * Update user's profile information.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProfile(int $userId, array $data): ProfileDTO
    {
        return DB::transaction(function () use ($userId, $data) {
            // Separate user data from profile data
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            $profileData = [
                'phone' => $data['phone'] ?? null,
                'bio' => $data['bio'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
            ];

            // Update user basic info
            $user = $this->profileRepository->updateUser($userId, $userData);

            // Update or create profile
            $this->profileRepository->updateProfile($userId, $profileData);

            // Reload with profile
            $user->load('profile');

            return ProfileDTO::fromModel($user);
        });
    }

    /**
     * Change user's password.
     */
    public function changePassword(int $userId, string $newPassword): bool
    {
        $result = $this->profileRepository->updatePassword($userId, $newPassword);

        // Optionally: logout all other sessions for security
        // Auth::guard()->logoutOtherDevices($newPassword);

        return $result;
    }
}
