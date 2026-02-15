<?php

declare(strict_types=1);

namespace App\Modules\Inventory\DTOs;

readonly class InventoryDTO
{
    public function __construct(
        public int $productId,
        public string $productName,
        public int $quantityAvailable,
        public int $quantityReserved,
        public int $totalStock,
        public int $lowStockThreshold,
        public bool $isLowStock,
        public bool $isOutOfStock,
    ) {}

    /**
     * Create DTO from Inventory model.
     */
    public static function fromModel(\App\Modules\Inventory\Models\Inventory $inventory): self
    {
        return new self(
            productId: $inventory->product_id,
            productName: $inventory->product->name ?? 'Unknown Product',
            quantityAvailable: $inventory->quantity_available,
            quantityReserved: $inventory->quantity_reserved,
            totalStock: $inventory->total_stock,
            lowStockThreshold: $inventory->low_stock_threshold,
            isLowStock: $inventory->isLowStock(),
            isOutOfStock: $inventory->isOutOfStock(),
        );
    }

    /**
     * Convert DTO to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'quantity_available' => $this->quantityAvailable,
            'quantity_reserved' => $this->quantityReserved,
            'total_stock' => $this->totalStock,
            'low_stock_threshold' => $this->lowStockThreshold,
            'is_low_stock' => $this->isLowStock,
            'is_out_of_stock' => $this->isOutOfStock,
        ];
    }
}
