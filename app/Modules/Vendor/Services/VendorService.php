<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\DTOs\VendorDTO;
use App\Modules\Vendor\Events\VendorApproved;
use App\Modules\Vendor\Events\VendorRegistered;
use App\Modules\Vendor\Events\VendorRejected;
use App\Modules\Vendor\Events\VendorSuspended;
use App\Modules\Vendor\Repositories\Contracts\VendorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class VendorService
{
    public function __construct(
        private readonly VendorRepositoryInterface $vendorRepository
    ) {}

    /**
     * Get paginated vendors with filters.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<VendorDTO>
     */
    public function getPaginatedVendors(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $vendors = $this->vendorRepository->getPaginated($filters, $perPage);

        $vendors->getCollection()->transform(fn ($vendor) => VendorDTO::fromModel($vendor));

        return $vendors;
    }

    /**
     * Get vendor by ID.
     */
    public function getVendorById(int $id): VendorDTO
    {
        $vendor = $this->vendorRepository->findById($id);

        if (! $vendor) {
            throw new InvalidArgumentException("Vendor with ID {$id} not found");
        }

        return VendorDTO::fromModel($vendor);
    }

    /**
     * Get vendor by slug.
     */
    public function getVendorBySlug(string $slug): VendorDTO
    {
        $vendor = $this->vendorRepository->findBySlug($slug);

        if (! $vendor) {
            throw new InvalidArgumentException("Vendor with slug '{$slug}' not found");
        }

        return VendorDTO::fromModel($vendor);
    }

    /**
     * Get vendor by user ID.
     */
    public function getVendorByUserId(int $userId): ?VendorDTO
    {
        $vendor = $this->vendorRepository->findByUserId($userId);

        return $vendor ? VendorDTO::fromModel($vendor) : null;
    }

    /**
     * Register a new vendor.
     *
     * @param  array<string, mixed>  $data
     */
    public function registerVendor(array $data): VendorDTO
    {
        // Check if user already has a vendor account
        $existingVendor = $this->vendorRepository->findByUserId($data['user_id']);

        if ($existingVendor) {
            throw new InvalidArgumentException('User already has a vendor account');
        }

        // Generate slug from business name
        $data['slug'] = $this->generateUniqueSlug($data['business_name']);

        // Set default status to pending
        $data['status'] = 'pending';

        // Handle logo upload if present
        if (! empty($data['logo'])) {
            $data['logo'] = $this->handleFileUpload($data['logo'], 'vendors/logos');
        }

        // Handle banner upload if present
        if (! empty($data['banner'])) {
            $data['banner'] = $this->handleFileUpload($data['banner'], 'vendors/banners');
        }

        $vendor = $this->vendorRepository->create($data);

        // Dispatch vendor registered event
        event(new VendorRegistered($vendor));

        return VendorDTO::fromModel($vendor);
    }

    /**
     * Update vendor information.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateVendor(int $id, array $data): VendorDTO
    {
        // Handle logo upload if present
        if (! empty($data['logo'])) {
            $vendor = $this->vendorRepository->findById($id);

            // Delete old logo
            if ($vendor && $vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }

            $data['logo'] = $this->handleFileUpload($data['logo'], 'vendors/logos');
        }

        // Handle banner upload if present
        if (! empty($data['banner'])) {
            $vendor = $this->vendorRepository->findById($id);

            // Delete old banner
            if ($vendor && $vendor->banner) {
                Storage::disk('public')->delete($vendor->banner);
            }

            $data['banner'] = $this->handleFileUpload($data['banner'], 'vendors/banners');
        }

        $success = $this->vendorRepository->update($id, $data);

        if (! $success) {
            throw new InvalidArgumentException("Failed to update vendor with ID {$id}");
        }

        return $this->getVendorById($id);
    }

    /**
     * Approve a vendor account.
     */
    public function approveVendor(int $id, int $approvedBy): VendorDTO
    {
        $success = $this->vendorRepository->approve($id, $approvedBy);

        if (! $success) {
            throw new InvalidArgumentException("Failed to approve vendor with ID {$id}");
        }

        $vendor = $this->vendorRepository->findById($id);

        // Dispatch vendor approved event
        event(new VendorApproved($vendor));

        return VendorDTO::fromModel($vendor);
    }

    /**
     * Reject a vendor account.
     */
    public function rejectVendor(int $id, string $reason): VendorDTO
    {
        $success = $this->vendorRepository->reject($id, $reason);

        if (! $success) {
            throw new InvalidArgumentException("Failed to reject vendor with ID {$id}");
        }

        $vendor = $this->vendorRepository->findById($id);

        // Dispatch vendor rejected event
        event(new VendorRejected($vendor));

        return VendorDTO::fromModel($vendor);
    }

    /**
     * Suspend a vendor account.
     */
    public function suspendVendor(int $id): VendorDTO
    {
        $success = $this->vendorRepository->suspend($id);

        if (! $success) {
            throw new InvalidArgumentException("Failed to suspend vendor with ID {$id}");
        }

        $vendor = $this->vendorRepository->findById($id);

        // Dispatch vendor suspended event
        event(new VendorSuspended($vendor));

        return VendorDTO::fromModel($vendor);
    }

    /**
     * Reactivate a suspended vendor.
     */
    public function reactivateVendor(int $id): VendorDTO
    {
        $success = $this->vendorRepository->reactivate($id);

        if (! $success) {
            throw new InvalidArgumentException("Failed to reactivate vendor with ID {$id}");
        }

        return $this->getVendorById($id);
    }

    /**
     * Get approved vendors.
     *
     * @return LengthAwarePaginator<VendorDTO>
     */
    public function getApprovedVendors(int $perPage = 15): LengthAwarePaginator
    {
        $vendors = $this->vendorRepository->getApproved($perPage);

        $vendors->getCollection()->transform(fn ($vendor) => VendorDTO::fromModel($vendor));

        return $vendors;
    }

    /**
     * Get pending vendors (for admin approval).
     *
     * @return LengthAwarePaginator<VendorDTO>
     */
    public function getPendingVendors(int $perPage = 15): LengthAwarePaginator
    {
        $vendors = $this->vendorRepository->getPending($perPage);

        $vendors->getCollection()->transform(fn ($vendor) => VendorDTO::fromModel($vendor));

        return $vendors;
    }

    /**
     * Get vendor statistics.
     *
     * @return array<string, mixed>
     */
    public function getVendorStatistics(int $vendorId): array
    {
        return $this->vendorRepository->getStatistics($vendorId);
    }

    /**
     * Delete a vendor account.
     */
    public function deleteVendor(int $id): bool
    {
        $vendor = $this->vendorRepository->findById($id);

        if (! $vendor) {
            throw new InvalidArgumentException("Vendor with ID {$id} not found");
        }

        // Delete logo and banner
        if ($vendor->logo) {
            Storage::disk('public')->delete($vendor->logo);
        }

        if ($vendor->banner) {
            Storage::disk('public')->delete($vendor->banner);
        }

        return $this->vendorRepository->delete($id);
    }

    /**
     * Generate a unique slug from business name.
     */
    private function generateUniqueSlug(string $businessName): string
    {
        $slug = Str::slug($businessName);
        $originalSlug = $slug;
        $count = 1;

        while ($this->vendorRepository->findBySlug($slug)) {
            $slug = $originalSlug.'-'.$count;
            $count++;
        }

        return $slug;
    }

    /**
     * Handle file upload and return storage path.
     *
     * @param  mixed  $file
     */
    private function handleFileUpload($file, string $directory): string
    {
        return $file->store($directory, 'public');
    }
}
