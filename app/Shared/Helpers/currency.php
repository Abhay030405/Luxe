<?php

use App\Models\SiteSetting;

if (! function_exists('currency')) {
    /**
     * Format a price with the configured currency symbol.
     */
    function currency(float|int|string $amount, bool $withSymbol = true): string
    {
        $amount = (float) $amount;
        $formatted = number_format($amount, 2);

        if ($withSymbol) {
            $symbol = SiteSetting::get('currency_symbol', '₹');

            return $symbol.' '.$formatted;
        }

        return $formatted;
    }
}

if (! function_exists('currency_symbol')) {
    /**
     * Get the configured currency symbol.
     */
    function currency_symbol(): string
    {
        return SiteSetting::get('currency_symbol', '₹');
    }
}

if (! function_exists('currency_code')) {
    /**
     * Get the configured currency code.
     */
    function currency_code(): string
    {
        return SiteSetting::get('currency_code', 'INR');
    }
}

if (! function_exists('site_setting')) {
    /**
     * Get a site setting value.
     */
    function site_setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}
