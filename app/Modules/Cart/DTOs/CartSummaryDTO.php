<?php

declare(strict_types=1);

namespace App\Modules\Cart\DTOs;

use Illuminate\Support\Collection;

readonly class CartSummaryDTO
{
    public function __construct(
        public Collection $items,
        public int $totalItems,
        public int $totalQuantity,
        public float $grandTotal,
    ) {}

    /**
     * Convert DTO to array for responses.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'items' => $this->items->map(fn (CartItemDTO $item) => $item->toArray())->toArray(),
            'total_items' => $this->totalItems,
            'total_quantity' => $this->totalQuantity,
            'grand_total' => $this->grandTotal,
        ];
    }
}
