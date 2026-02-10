<?php

declare(strict_types=1);

namespace App\Modules\User\Services;

use App\Modules\User\DTOs\AddressDTO;
use App\Modules\User\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AddressService
{
    public function __construct(
        private readonly AddressRepositoryInterface $addressRepository
    ) {}

    /**
     * Get all addresses for a user.
     *
     * @return Collection<int, AddressDTO>
     */
    public function getAllAddressesForUser(int $userId): Collection
    {
        $addresses = $this->addressRepository->getAllForUser($userId);

        return $addresses->map(fn ($address) => AddressDTO::fromModel($address));
    }

    /**
     * Get a specific address for a user.
     */
    public function getAddressForUser(int $addressId, int $userId): AddressDTO
    {
        $address = $this->addressRepository->findByIdAndUser($addressId, $userId);

        if (! $address) {
            throw ValidationException::withMessages([
                'address' => ['Address not found or you do not have permission to access it.'],
            ]);
        }

        return AddressDTO::fromModel($address);
    }

    /**
     * Create a new address for a user.
     *
     * @param  array<string, mixed>  $data
     */
    public function createAddress(int $userId, array $data): AddressDTO
    {
        return DB::transaction(function () use ($userId, $data) {
            // If this is being set as default, unset all other defaults
            if ($data['is_default'] ?? false) {
                $this->addressRepository->unsetDefaultForUser($userId);
            } else {
                // If this is the first address, make it default automatically
                $addressCount = $this->addressRepository->countForUser($userId);
                if ($addressCount === 0) {
                    $data['is_default'] = true;
                }
            }

            $address = $this->addressRepository->create($userId, $data);

            return AddressDTO::fromModel($address);
        });
    }

    /**
     * Update an address.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateAddress(int $addressId, int $userId, array $data): AddressDTO
    {
        return DB::transaction(function () use ($addressId, $userId, $data) {
            // Verify ownership
            $address = $this->addressRepository->findByIdAndUser($addressId, $userId);

            if (! $address) {
                throw ValidationException::withMessages([
                    'address' => ['Address not found or you do not have permission to modify it.'],
                ]);
            }

            // If setting as default, unset all other defaults
            if (($data['is_default'] ?? false) && ! $address->is_default) {
                $this->addressRepository->unsetDefaultForUser($userId);
            }

            $updatedAddress = $this->addressRepository->update($addressId, $data);

            return AddressDTO::fromModel($updatedAddress);
        });
    }

    /**
     * Delete an address.
     */
    public function deleteAddress(int $addressId, int $userId): bool
    {
        return DB::transaction(function () use ($addressId, $userId) {
            // Verify ownership
            $address = $this->addressRepository->findByIdAndUser($addressId, $userId);

            if (! $address) {
                throw ValidationException::withMessages([
                    'address' => ['Address not found or you do not have permission to delete it.'],
                ]);
            }

            // Prevent deletion if it's the only address
            $addressCount = $this->addressRepository->countForUser($userId);
            if ($addressCount === 1) {
                throw ValidationException::withMessages([
                    'address' => ['You cannot delete your only address. Please add another address first.'],
                ]);
            }

            // If deleting default address, set another as default
            if ($address->is_default) {
                $deleted = $this->addressRepository->delete($addressId);

                // Set the most recent address as default
                $newDefault = $this->addressRepository->getAllForUser($userId)->first();
                if ($newDefault) {
                    $this->addressRepository->setAsDefault($newDefault->id);
                }

                return $deleted;
            }

            return $this->addressRepository->delete($addressId);
        });
    }

    /**
     * Set an address as default.
     */
    public function setDefaultAddress(int $addressId, int $userId): AddressDTO
    {
        return DB::transaction(function () use ($addressId, $userId) {
            // Verify ownership
            $address = $this->addressRepository->findByIdAndUser($addressId, $userId);

            if (! $address) {
                throw ValidationException::withMessages([
                    'address' => ['Address not found or you do not have permission to modify it.'],
                ]);
            }

            // Set as default (this will unset others internally)
            $this->addressRepository->setAsDefault($addressId);

            // Return updated address
            $updatedAddress = $this->addressRepository->findById($addressId);

            return AddressDTO::fromModel($updatedAddress);
        });
    }

    /**
     * Get the default address for a user.
     */
    public function getDefaultAddress(int $userId): ?AddressDTO
    {
        $address = $this->addressRepository->getDefaultForUser($userId);

        return $address ? AddressDTO::fromModel($address) : null;
    }
}
