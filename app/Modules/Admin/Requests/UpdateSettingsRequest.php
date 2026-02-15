<?php

declare(strict_types=1);

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Parse currency dropdown value (e.g., "INR|₹") into separate fields
        if ($this->has('currency') && str_contains($this->currency, '|')) {
            [$code, $symbol] = explode('|', $this->currency, 2);
            $this->merge([
                'currency_code' => $code,
                'currency_symbol' => $symbol,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'regex:/^[A-Z]{3}\|.+$/'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'link_about_us' => ['nullable', 'string', 'max:5000'],
            'link_contact' => ['nullable', 'string', 'max:5000'],
            'link_faqs' => ['nullable', 'string', 'max:5000'],
            'link_return_policy' => ['nullable', 'string', 'max:5000'],
            'footer_about' => ['nullable', 'string', 'max:500'],
            'footer_email' => ['nullable', 'email', 'max:255'],
            'footer_phone' => ['nullable', 'string', 'max:50'],
            'footer_address' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'site_name' => 'website name',
            'site_tagline' => 'tagline',
            'currency' => 'currency',
            'social_facebook' => 'Facebook URL',
            'social_instagram' => 'Instagram URL',
            'social_twitter' => 'Twitter URL',
            'link_about_us' => 'About Us content',
            'link_contact' => 'Contact information',
            'link_faqs' => 'FAQs content',
            'link_return_policy' => 'Return Policy',
            'footer_about' => 'about text',
            'footer_email' => 'email address',
            'footer_phone' => 'phone number',
            'footer_address' => 'address',
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
            'site_name.required' => 'Website name is required.',
            'currency.required' => 'Please select a currency.',
            'currency.regex' => 'Invalid currency format.',
            'footer_email.email' => 'Please enter a valid email address.',
            'social_facebook.url' => 'Please enter a valid Facebook URL.',
            'social_instagram.url' => 'Please enter a valid Instagram URL.',
            'social_twitter.url' => 'Please enter a valid Twitter URL.',
        ];
    }
}
