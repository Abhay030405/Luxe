<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\ChangePasswordRequest;
use App\Modules\User\Requests\UpdateProfileRequest;
use App\Modules\User\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService
    ) {}

    /**
     * Display the user's profile.
     */
    public function show(): View
    {
        $profile = $this->profileService->getAuthenticatedUserProfile();

        return view('pages.user.profile', [
            'profile' => $profile,
        ]);
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit(): View
    {
        $profile = $this->profileService->getAuthenticatedUserProfile();

        return view('pages.user.edit-profile', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the user's profile.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile(
            $request->user()->id,
            $request->validated()
        );

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the change password form.
     */
    public function editPassword(): View
    {
        return view('pages.user.change-password');
    }

    /**
     * Change the user's password.
     */
    public function updatePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $this->profileService->changePassword(
            $request->user()->id,
            $request->validated()['password']
        );

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully.');
    }
}
