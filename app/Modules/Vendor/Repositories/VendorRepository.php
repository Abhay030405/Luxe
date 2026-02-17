<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Repositories;

use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Repositories\Contracts\VendorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VendorRepository implements VendorRepositoryInterface
{
    public function __construct(
        private readonly Vendor $model
    ) {}

    /**
     * Get all vendors.
     */
    public function all(): Collection
    {
        return $this->model->with(['user', 'products'])->get();
    }

    /**
     * Get paginated vendors with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['user']);

        // Filter by status
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by search term
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by business type
        if (! empty($filters['business_type'])) {
            $query->where('business_type', $filters['business_type']);
        }

        // Filter by city
        if (! empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Find vendor by ID.
     */
    public function findById(int $id): ?Vendor
    {
        return $this->model->with(['user', 'products', 'approvedBy'])->find($id);
    }

    /**
     * Find vendor by slug.
     */
    public function findBySlug(string $slug): ?Vendor
    {
        return $this->model->with(['user', 'products'])->where('slug', $slug)->first();
    }

    /**
     * Find vendor by user ID.
     */
    public function findByUserId(int $userId): ?Vendor
    {
        return $this->model->with(['products'])->where('user_id', $userId)->first();
    }

    /**
     * Create a new vendor.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Vendor
    {
        return $this->model->create($data);
    }

    /**
     * Update a vendor.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): bool
    {
        $vendor = $this->model->find($id);

        if (! $vendor) {
            return false;
        }

        return $vendor->update($data);
    }

    /**
     * Delete a vendor.
     */
    public function delete(int $id): bool
    {
        $vendor = $this->model->find($id);

        if (! $vendor) {
            return false;
        }

        return $vendor->delete();
    }

    /**
     * Get approved vendors.
     */
    public function getApproved(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->approved()
            ->with(['user'])
            ->withCount('products')
            ->orderBy('business_name')
            ->paginate($perPage);
    }

    /**
     * Get pending vendors.
     */
    public function getPending(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->pending()
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Approve a vendor.
     */
    public function approve(int $id, int $approvedBy): bool
    {
        return $this->update($id, [
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject a vendor.
     */
    public function reject(int $id, string $reason): bool
    {
        return $this->update($id, [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    /**
     * Suspend a vendor.
     */
    public function suspend(int $id): bool
    {
        return $this->update($id, [
            'status' => 'suspended',
        ]);
    }

    /**
     * Reactivate a suspended vendor.
     */
    public function reactivate(int $id): bool
    {
        return $this->update($id, [
            'status' => 'approved',
        ]);
    }

    /**
     * Get vendor statistics.
     *
     * @return array<string, mixed>
     */
    public function getStatistics(int $vendorId): array
    {
        $vendor = $this->findById($vendorId);

        if (! $vendor) {
            return [
                'total_products' => 0,
                'active_products' => 0,
                'total_orders' => 0,
                'total_earnings' => 0,
            ];
        }

        $totalRevenue = $vendor->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->sum('order_items.subtotal');

        // Calculate earnings after commission
        $commissionRate = $vendor->commission_rate ?? 0;
        $totalEarnings = $totalRevenue * (1 - ($commissionRate / 100));

        return [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->where('status', 'active')->count(),
            'total_orders' => $vendor->products()
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->distinct('order_items.order_id')
                ->count('order_items.order_id'),
            'total_earnings' => $totalEarnings,
        ];
    }
}
