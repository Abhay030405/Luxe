<?php

declare(strict_types=1);

namespace App\Modules\Order\Repositories\Contracts;

use App\Modules\Order\Models\VendorOrder;
use App\Shared\Enums\VendorOrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface for VendorOrder repository.
 */
interface VendorOrderRepositoryInterface
{
    public function findById(int $id): ?VendorOrder;

    public function findByIdOrFail(int $id): VendorOrder;

    public function findByVendorOrderNumber(string $vendorOrderNumber): ?VendorOrder;

    public function getVendorOrders(int $vendorId, int $perPage = 15): LengthAwarePaginator;

    public function getVendorOrdersByStatus(int $vendorId, VendorOrderStatus $status): Collection;

    public function getPendingVendorOrders(int $vendorId): Collection;

    public function getActiveVendorOrders(int $vendorId): Collection;

    public function vendorOwnsOrder(int $vendorId, int $vendorOrderId): bool;

    public function updateStatus(int $vendorOrderId, VendorOrderStatus $status, ?array $additionalData = null): bool;

    public function acceptOrder(int $vendorOrderId, ?string $notes = null): bool;

    public function packOrder(int $vendorOrderId, ?string $notes = null): bool;

    public function shipOrder(int $vendorOrderId, string $trackingNumber, string $carrier, ?string $notes = null): bool;

    public function deliverOrder(int $vendorOrderId): bool;

    public function cancelOrder(int $vendorOrderId, string $reason): bool;

    public function rejectOrder(int $vendorOrderId, string $reason): bool;

    public function getVendorOrdersForCustomerOrder(int $orderId): Collection;

    public function getVendorOrderStats(int $vendorId): array;
}
