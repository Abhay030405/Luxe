<?php

declare(strict_types=1);

namespace App\Modules\Order\DTOs;

use App\Models\Address;

/**
 * DTO for checkout data flow.
 * Encapsulates all data needed for the checkout process.
 */
readonly class CheckoutDTO
{
    /**
     * @param  array<CartItemDTO>  $items
     */
    public function __construct(
        public int $userId,
        public int $addressId,
        public Address $address,
        public array $items,
        public float $subtotal,
        public float $tax,
        public float $shippingFee,
        public float $total,
        public ?string $customerNotes = null,
    ) {}

    /**
     * Create from request data.
     */
    public static function fromRequest(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            addressId: (int) $data['address_id'],
            address: Address::findOrFail($data['address_id']),
            items: $data['items'] ?? [],
            subtotal: (float) $data['subtotal'],
            tax: (float) ($data['tax'] ?? 0),
            shippingFee: (float) ($data['shipping_fee'] ?? 0),
            total: (float) $data['total'],
            customerNotes: $data['customer_notes'] ?? null,
        );
    }

    /**
     * Get address as array snapshot.
     */
    public function getAddressSnapshot(): array
    {
        return [
            'full_name' => $this->address->full_name,
            'phone' => $this->address->phone,
            'address_line_1' => $this->address->address_line_1,
            'address_line_2' => $this->address->address_line_2,
            'city' => $this->address->city,
            'state' => $this->address->state,
            'postal_code' => $this->address->postal_code,
            'country' => $this->address->country,
            'address_type' => $this->address->address_type,
        ];
    }

    /**
     * Convert to array for storage.
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'address_id' => $this->addressId,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'shipping_fee' => $this->shippingFee,
            'total' => $this->total,
            'customer_notes' => $this->customerNotes,
            'address_snapshot' => $this->getAddressSnapshot(),
        ];
    }
}
