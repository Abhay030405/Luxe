<?php

declare(strict_types=1);

namespace App\Modules\Order\DTOs;

use App\Modules\Cart\Models\CartItem;

/**
 * DTO for cart items during checkout.
 * Represents a single item in the checkout flow.
 */
readonly class CartItemDTO
{
    public function __construct(
        public int $productId,
        public string $productName,
        public ?string $productSku,
        public float $price,
        public int $quantity,
        public float $subtotal,
    ) {}

    /**
     * Create from CartItem model.
     */
    public static function fromCartItem(CartItem $cartItem): self
    {
        return new self(
            productId: $cartItem->product_id,
            productName: $cartItem->product->name,
            productSku: $cartItem->product->sku ?? null,
            price: (float) $cartItem->price_at_time,
            quantity: $cartItem->quantity,
            subtotal: (float) $cartItem->subtotal,
        );
    }

    /**
     * Convert to array for order item creation.
     */
    public function toOrderItemArray(int $orderId): array
    {
        return [
            'order_id' => $orderId,
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'product_sku' => $this->productSku,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
        ];
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'product_sku' => $this->productSku,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
        ];
    }
}
