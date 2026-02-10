<?php

declare(strict_types=1);

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Modules\Auth\DTOs\AuthUserDTO;
use App\Modules\Auth\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Register a new user.
     *
     * @param  array<string, mixed>  $data
     */
    public function register(array $data): AuthUserDTO
    {
        // Check if email already exists
        if ($this->userRepository->emailExists($data['email'])) {
            throw ValidationException::withMessages([
                'email' => ['The email has already been taken.'],
            ]);
        }

        // Create the user
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // Password will be hashed automatically by the model
        ]);

        // Automatically login the user after registration
        Auth::login($user);

        return AuthUserDTO::fromModel($user);
    }

    /**
     * Authenticate a user.
     *
     * @param  array<string, mixed>  $credentials
     */
    public function login(array $credentials, bool $remember = false): AuthUserDTO
    {
        // Find user by email
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create session
        Auth::login($user, $remember);

        // Regenerate session to prevent session fixation
        request()->session()->regenerate();

        return AuthUserDTO::fromModel($user);
    }

    /**
     * Logout the authenticated user.
     */
    public function logout(): void
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Get the currently authenticated user.
     */
    public function getAuthenticatedUser(): ?AuthUserDTO
    {
        $user = Auth::user();

        return $user ? AuthUserDTO::fromModel($user) : null;
    }
}
