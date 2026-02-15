<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdjustInventoryRequest extends FormRequest
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
            'quantity_change' => ['required', 'integer', 'min:-9999', 'max:9999'],
            'reason' => ['nullable', 'string', 'max:255'],
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
            'quantity_change.required' => 'Quantity change is required.',
            'quantity_change.integer' => 'Quantity change must be a number.',
            'quantity_change.min' => 'Quantity change cannot be less than -9999.',
            'quantity_change.max' => 'Quantity change cannot exceed 9999.',
            'reason.max' => 'Reason cannot exceed 255 characters.',
        ];
    }
}
