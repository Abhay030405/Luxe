<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['sometimes', 'required', 'exists:products,id'],
            'quantity_available' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
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
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'The selected product does not exist.',
            'quantity_available.required' => 'Available quantity is required.',
            'quantity_available.integer' => 'Available quantity must be a number.',
            'quantity_available.min' => 'Available quantity cannot be negative.',
            'low_stock_threshold.required' => 'Low stock threshold is required.',
            'low_stock_threshold.integer' => 'Low stock threshold must be a number.',
            'low_stock_threshold.min' => 'Low stock threshold cannot be negative.',
        ];
    }
}
