<?php

declare(strict_types=1);

namespace App\Modules\User\Repositories;

use App\Models\Address;
use App\Modules\User\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AddressRepository implements AddressRepositoryInterface
{
    public function __construct(
        private readonly Address $model
    ) {}

    /**
     * Get all addresses for a user.
     *
     * @return Collection<int, Address>
     */
    public function getAllForUser(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Find an address by ID.
     */
    public function findById(int $addressId): ?Address
    {
        return $this->model->find($addressId);
    }

    /**
     * Find an address by ID and user ID (for authorization).
     */
    public function findByIdAndUser(int $addressId, int $userId): ?Address
    {
        return $this->model
            ->where('id', $addressId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Create a new address for a user.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(int $userId, array $data): Address
    {
        $data['user_id'] = $userId;

        return $this->model->create($data);
    }

    /**
     * Update an address.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $addressId, array $data): Address
    {
        $address = $this->model->findOrFail($addressId);

        $address->update($data);

        return $address->fresh();
    }

    /**
     * Delete an address.
     */
    public function delete(int $addressId): bool
    {
        $address = $this->model->findOrFail($addressId);

        return $address->delete();
    }

    /**
     * Get the default address for a user.
     */
    public function getDefaultForUser(int $userId): ?Address
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_default', true)
            ->first();
    }

    /**
     * Unset all default addresses for a user.
     */
    public function unsetDefaultForUser(int $userId): void
    {
        $this->model
            ->where('user_id', $userId)
            ->update(['is_default' => false]);
    }

    /**
     * Set an address as default.
     */
    public function setAsDefault(int $addressId): bool
    {
        $address = $this->model->findOrFail($addressId);

        // First, unset all defaults for this user
        $this->unsetDefaultForUser($address->user_id);

        // Then set this one as default
        return $address->update(['is_default' => true]);
    }

    /**
     * Count addresses for a user.
     */
    public function countForUser(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Count default addresses for a user.
     */
    public function countDefaultForUser(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_default', true)
            ->count();
    }
}
