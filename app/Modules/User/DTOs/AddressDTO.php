<?php

declare(strict_types=1);

namespace App\Modules\User\DTOs;

use App\Models\Address;

readonly class AddressDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $fullName,
        public string $phone,
        public string $addressLine1,
        public ?string $addressLine2,
        public string $city,
        public string $state,
        public string $postalCode,
        public string $country,
        public bool $isDefault,
        public string $addressType,
        public string $fullAddress,
    ) {}

    public static function fromModel(Address $address): self
    {
        return new self(
            id: $address->id,
            userId: $address->user_id,
            fullName: $address->full_name,
            phone: $address->phone,
            addressLine1: $address->address_line_1,
            addressLine2: $address->address_line_2,
            city: $address->city,
            state: $address->state,
            postalCode: $address->postal_code,
            country: $address->country,
            isDefault: $address->is_default,
            addressType: $address->address_type,
            fullAddress: $address->full_address,
        );
    }

    /**
     * Convert DTO to array for responses.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'full_name' => $this->fullName,
            'phone' => $this->phone,
            'address_line_1' => $this->addressLine1,
            'address_line_2' => $this->addressLine2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'is_default' => $this->isDefault,
            'address_type' => $this->addressType,
            'full_address' => $this->fullAddress,
        ];
    }
}
