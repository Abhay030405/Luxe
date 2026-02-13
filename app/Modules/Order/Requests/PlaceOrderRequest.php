<?php

declare(strict_types=1);

namespace App\Modules\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for placing an order.
 * Validates all checkout data before order creation.
 */
class PlaceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'address_id.required' => 'Please select a delivery address.',
            'address_id.exists' => 'The selected address is invalid.',
            'customer_notes.max' => 'Customer notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get validated address ID.
     */
    public function getAddressId(): int
    {
        return (int) $this->validated()['address_id'];
    }

    /**
     * Get customer notes.
     */
    public function getCustomerNotes(): ?string
    {
        return $this->validated()['customer_notes'] ?? null;
    }
}
