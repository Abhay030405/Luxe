<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Repositories\Contracts;

use App\Modules\Vendor\Models\Vendor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface VendorRepositoryInterface
{
    /**
     * Get all vendors.
     */
    public function all(): Collection;

    /**
     * Get paginated vendors with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find vendor by ID.
     */
    public function findById(int $id): ?Vendor;

    /**
     * Find vendor by slug.
     */
    public function findBySlug(string $slug): ?Vendor;

    /**
     * Find vendor by user ID.
     */
    public function findByUserId(int $userId): ?Vendor;

    /**
     * Create a new vendor.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Vendor;

    /**
     * Update a vendor.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a vendor.
     */
    public function delete(int $id): bool;

    /**
     * Get approved vendors.
     */
    public function getApproved(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get pending vendors.
     */
    public function getPending(int $perPage = 15): LengthAwarePaginator;

    /**
     * Approve a vendor.
     */
    public function approve(int $id, int $approvedBy): bool;

    /**
     * Reject a vendor.
     */
    public function reject(int $id, string $reason): bool;

    /**
     * Suspend a vendor.
     */
    public function suspend(int $id): bool;

    /**
     * Reactivate a suspended vendor.
     */
    public function reactivate(int $id): bool;

    /**
     * Get vendor statistics.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(int $vendorId): array;
}
