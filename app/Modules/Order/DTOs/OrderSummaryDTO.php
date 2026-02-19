<?php

declare(strict_types=1);

namespace App\Modules\Order\DTOs;

use App\Modules\Order\Models\Order;

/**
 * DTO for order summary display.
 * Used for checkout preview and order confirmation.
 */
readonly class OrderSummaryDTO
{
    /**
     * @param  array<CartItemDTO>  $items
     */
    public function __construct(
        public array $items,
        public float $subtotal,
        public float $tax,
        public float $shippingFee,
        public float $total,
        public int $totalItems,
    ) {}

    /**
     * Create from array of cart items.
     *
     * @param  array<CartItemDTO>  $items
     */
    public static function fromCartItems(array $items, float $taxRate = 0, float $shippingFee = 0): self
    {
        $subtotal = array_reduce(
            $items,
            fn (float $carry, CartItemDTO $item) => $carry + $item->subtotal,
            0
        );

        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax + $shippingFee;

        $totalItems = array_reduce(
            $items,
            fn (int $carry, CartItemDTO $item) => $carry + $item->quantity,
            0
        );

        return new self(
            items: $items,
            subtotal: $subtotal,
            tax: $tax,
            shippingFee: $shippingFee,
            total: $total,
            totalItems: $totalItems,
        );
    }

    /**
     * Create from Order model.
     */
    public static function fromOrder(Order $order): self
    {
        $items = $order->items->map(
            fn ($item) => new CartItemDTO(
                productId: $item->product_id,
                productName: $item->product_name,
                productSku: $item->product_sku,
                price: (float) $item->price,
                quantity: $item->quantity,
                subtotal: (float) $item->subtotal,
            )
        )->toArray();

        return new self(
            items: $items,
            subtotal: (float) $order->subtotal,
            tax: (float) $order->tax,
            shippingFee: (float) $order->shipping_fee,
            total: (float) $order->total_amount,
            totalItems: $order->items->sum('quantity'),
        );
    }

    /**
     * Get formatted subtotal.
     */
    public function getFormattedSubtotal(): string
    {
        return '₹'.number_format((float) $this->subtotal, 2);
    }

    /**
     * Get formatted tax.
     */
    public function getFormattedTax(): string
    {
        return '₹'.number_format((float) $this->tax, 2);
    }

    /**
     * Get formatted shipping fee.
     */
    public function getFormattedShippingFee(): string
    {
        return '₹'.number_format((float) $this->shippingFee, 2);
    }

    /**
     * Get formatted total.
     */
    public function getFormattedTotal(): string
    {
        return '₹'.number_format((float) $this->total, 2);
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'items' => array_map(fn (CartItemDTO $item) => $item->toArray(), $this->items),
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'shipping_fee' => $this->shippingFee,
            'total' => $this->total,
            'total_items' => $this->totalItems,
            'formatted_subtotal' => $this->getFormattedSubtotal(),
            'formatted_tax' => $this->getFormattedTax(),
            'formatted_shipping_fee' => $this->getFormattedShippingFee(),
            'formatted_total' => $this->getFormattedTotal(),
        ];
    }
}
