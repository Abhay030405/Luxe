<?php

declare(strict_types=1);

namespace App\Modules\Auth\DTOs;

use App\Models\User;

readonly class AuthUserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $emailVerifiedAt = null,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at?->toDateTimeString(),
        );
    }
}
