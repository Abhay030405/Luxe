<?php

declare(strict_types=1);

namespace App\Modules\Order\DTOs;

use App\Modules\Order\Models\Order;
use App\Shared\Enums\OrderStatus;

/**
 * DTO for creating a new order.
 * Contains all validated data needed for order creation.
 */
readonly class CreateOrderDTO
{
    /**
     * @param  array<CartItemDTO>  $items
     */
    public function __construct(
        public int $userId,
        public string $orderNumber,
        public OrderStatus $status,
        public float $subtotal,
        public float $tax,
        public float $shippingFee,
        public float $totalAmount,
        public array $addressSnapshot,
        public array $items,
        public ?string $customerNotes = null,
    ) {}

    /**
     * Create from CheckoutDTO.
     */
    public static function fromCheckoutDTO(CheckoutDTO $checkout, string $orderNumber): self
    {
        return new self(
            userId: $checkout->userId,
            orderNumber: $orderNumber,
            status: OrderStatus::Pending,
            subtotal: $checkout->subtotal,
            tax: $checkout->tax,
            shippingFee: $checkout->shippingFee,
            totalAmount: $checkout->total,
            addressSnapshot: $checkout->getAddressSnapshot(),
            items: $checkout->items,
            customerNotes: $checkout->customerNotes,
        );
    }

    /**
     * Convert to array for order creation.
     */
    public function toOrderArray(): array
    {
        return [
            'user_id' => $this->userId,
            'order_number' => $this->orderNumber,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'shipping_fee' => $this->shippingFee,
            'total_amount' => $this->totalAmount,
            'address_snapshot' => $this->addressSnapshot,
            'customer_notes' => $this->customerNotes,
        ];
    }
}
