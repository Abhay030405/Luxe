<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Modules\Admin\Requests\UpdateSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display site settings page.
     */
    public function index(): View
    {
        $settings = SiteSetting::all()->pluck('setting_value', 'setting_key');

        return view('pages.admin.settings.index', compact('settings'));
    }

    /**
     * Update site settings.
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Remove the currency dropdown field as it was already split into currency_code and currency_symbol
        unset($validated['currency']);

        foreach ($validated as $key => $value) {
            // Determine type based on key
            $type = match ($key) {
                'site_name', 'site_tagline', 'currency_symbol', 'currency_code',
                'footer_email', 'footer_phone', 'footer_address',
                'social_facebook', 'social_instagram', 'social_twitter' => 'string',

                'footer_about',
                'link_about_us', 'link_contact', 'link_faqs', 'link_return_policy' => 'text',

                default => 'string',
            };

            SiteSetting::set($key, (string) $value, $type);
        }

        SiteSetting::clearCache();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Site settings updated successfully!');
    }
}
