<?php

declare(strict_types=1);

namespace App\Modules\User\DTOs;

use App\Models\User;

readonly class ProfileDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $phone = null,
        public ?string $bio = null,
        public ?string $dateOfBirth = null,
        public ?string $gender = null,
        public ?string $emailVerifiedAt = null,
    ) {}

    public static function fromModel(User $user): self
    {
        $profile = $user->profile;

        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            phone: $profile?->phone,
            bio: $profile?->bio,
            dateOfBirth: $profile?->date_of_birth?->format('Y-m-d'),
            gender: $profile?->gender,
            emailVerifiedAt: $user->email_verified_at?->toDateTimeString(),
        );
    }

    /**
     * Convert DTO to array for responses.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'date_of_birth' => $this->dateOfBirth,
            'gender' => $this->gender,
            'email_verified_at' => $this->emailVerifiedAt,
        ];
    }
}
