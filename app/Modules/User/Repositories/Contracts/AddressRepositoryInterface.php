<?php

declare(strict_types=1);

namespace App\Modules\User\Repositories\Contracts;

use App\Models\Address;
use Illuminate\Database\Eloquent\Collection;

interface AddressRepositoryInterface
{
    /**
     * Get all addresses for a user.
     *
     * @return Collection<int, Address>
     */
    public function getAllForUser(int $userId): Collection;

    /**
     * Find an address by ID.
     */
    public function findById(int $addressId): ?Address;

    /**
     * Find an address by ID and user ID (for authorization).
     */
    public function findByIdAndUser(int $addressId, int $userId): ?Address;

    /**
     * Create a new address for a user.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(int $userId, array $data): Address;

    /**
     * Update an address.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $addressId, array $data): Address;

    /**
     * Delete an address.
     */
    public function delete(int $addressId): bool;

    /**
     * Get the default address for a user.
     */
    public function getDefaultForUser(int $userId): ?Address;

    /**
     * Unset all default addresses for a user.
     */
    public function unsetDefaultForUser(int $userId): void;

    /**
     * Set an address as default.
     */
    public function setAsDefault(int $addressId): bool;

    /**
     * Count addresses for a user.
     */
    public function countForUser(int $userId): int;

    /**
     * Count default addresses for a user.
     */
    public function countDefaultForUser(int $userId): int;
}
