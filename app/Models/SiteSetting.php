<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
    ];

    /**
     * Get a setting value by key with caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('setting_key', $key)->first();

            if (! $setting) {
                return $default;
            }

            return match ($setting->setting_type) {
                'boolean' => (bool) $setting->setting_value,
                'json' => json_decode($setting->setting_value, true),
                default => $setting->setting_value,
            };
        });
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value, string $type = 'string'): void
    {
        $settingValue = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => (string) $value,
        };

        static::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $settingValue,
                'setting_type' => $type,
            ]
        );

        Cache::forget("setting_{$key}");
    }

    /**
     * Get all settings as key-value pairs.
     */
    public static function getAll(): array
    {
        return Cache::remember('all_settings', 3600, function (): array {
            return static::all()->pluck('setting_value', 'setting_key')->toArray();
        });
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
