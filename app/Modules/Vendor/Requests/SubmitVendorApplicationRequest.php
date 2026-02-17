<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitVendorApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public can apply
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:vendor_applications,email'],
            'phone' => ['required', 'string', 'max:20'],
            'shop_name' => ['required', 'string', 'max:255'],
            'business_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'product_category' => ['required', 'string', 'max:255'],
            'estimated_products' => ['required', 'integer', 'min:1', 'max:100000'],
            'pickup_address' => ['nullable', 'string', 'max:500'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'id_proof' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'email.unique' => 'This email is already used in another vendor application.',
            'phone.required' => 'Phone number is required.',
            'shop_name.required' => 'Shop/Brand name is required.',
            'business_address.required' => 'Business address is required.',
            'city.required' => 'City is required.',
            'state.required' => 'State is required.',
            'pincode.required' => 'Pincode/ZIP code is required.',
            'product_category.required' => 'Product category is required.',
            'estimated_products.required' => 'Estimated number of products is required.',
            'estimated_products.integer' => 'Estimated products must be a number.',
            'estimated_products.min' => 'Estimated products must be at least 1.',
            'id_proof.mimes' => 'ID proof must be a PDF or image file (jpg, jpeg, png).',
            'id_proof.max' => 'ID proof file size must not exceed 5MB.',
        ];
    }
}
