<?php

declare(strict_types=1);

namespace App\Modules\Vendor\DTOs;

use App\Modules\Vendor\Models\Vendor;

readonly class VendorDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $businessName,
        public string $slug,
        public ?string $description,
        public ?string $email,
        public ?string $phone,
        public ?string $logo,
        public ?string $banner,
        public ?string $addressLine1,
        public ?string $addressLine2,
        public ?string $city,
        public ?string $state,
        public ?string $postalCode,
        public string $country,
        public ?string $businessType,
        public ?string $taxId,
        public ?string $registrationNumber,
        public string $status,
        public float $commissionRate,
        public ?string $bankName,
        public ?string $bankAccountNumber,
        public ?string $bankAccountHolder,
        public ?string $bankRoutingNumber,
        public ?array $socialLinks,
        public ?array $metaData,
        public ?int $approvedBy,
        public ?string $approvedAt,
        public ?string $rejectionReason,
        public string $createdAt,
        public string $updatedAt,
        public ?string $userName = null,
        public ?int $productsCount = null,
    ) {}

    /**
     * Create DTO from Vendor model.
     */
    public static function fromModel(Vendor $vendor): self
    {
        return new self(
            id: $vendor->id,
            userId: $vendor->user_id,
            businessName: $vendor->business_name,
            slug: $vendor->slug,
            description: $vendor->description,
            email: $vendor->email,
            phone: $vendor->phone,
            logo: $vendor->logo,
            banner: $vendor->banner,
            addressLine1: $vendor->address_line1,
            addressLine2: $vendor->address_line2,
            city: $vendor->city,
            state: $vendor->state,
            postalCode: $vendor->postal_code,
            country: $vendor->country,
            businessType: $vendor->business_type,
            taxId: $vendor->tax_id,
            registrationNumber: $vendor->registration_number,
            status: $vendor->status,
            commissionRate: (float) $vendor->commission_rate,
            bankName: $vendor->bank_name,
            bankAccountNumber: $vendor->bank_account_number,
            bankAccountHolder: $vendor->bank_account_holder,
            bankRoutingNumber: $vendor->bank_routing_number,
            socialLinks: $vendor->social_links,
            metaData: $vendor->meta_data,
            approvedBy: $vendor->approved_by,
            approvedAt: $vendor->approved_at?->toDateTimeString(),
            rejectionReason: $vendor->rejection_reason,
            createdAt: $vendor->created_at->toDateTimeString(),
            updatedAt: $vendor->updated_at->toDateTimeString(),
            userName: $vendor->user->name ?? null,
            productsCount: $vendor->products_count ?? $vendor->products()->count(),
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
            'id' => $this->id,
            'user_id' => $this->userId,
            'business_name' => $this->businessName,
            'slug' => $this->slug,
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'logo' => $this->logo,
            'banner' => $this->banner,
            'address_line1' => $this->addressLine1,
            'address_line2' => $this->addressLine2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'business_type' => $this->businessType,
            'tax_id' => $this->taxId,
            'registration_number' => $this->registrationNumber,
            'status' => $this->status,
            'commission_rate' => $this->commissionRate,
            'bank_name' => $this->bankName,
            'bank_account_number' => $this->bankAccountNumber,
            'bank_account_holder' => $this->bankAccountHolder,
            'bank_routing_number' => $this->bankRoutingNumber,
            'social_links' => $this->socialLinks,
            'meta_data' => $this->metaData,
            'approved_by' => $this->approvedBy,
            'approved_at' => $this->approvedAt,
            'rejection_reason' => $this->rejectionReason,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'user_name' => $this->userName,
            'products_count' => $this->productsCount,
        ];
    }
}
