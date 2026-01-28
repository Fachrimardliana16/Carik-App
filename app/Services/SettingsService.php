<?php

namespace App\Services;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = CompanySetting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value): void
    {
        CompanySetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("setting_{$key}");
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $keys = CompanySetting::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
    }

    /**
     * Get company name
     */
    public static function getCompanyName(): string
    {
        return self::get('company_name', 'SIPD');
    }

    /**
     * Get logo path for light mode
     */
    public static function getLogoLight(): ?string
    {
        $logo = self::get('logo_light');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Get logo path for dark mode
     */
    public static function getLogoDark(): ?string
    {
        $logo = self::get('logo_dark');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Get favicon path
     */
    public static function getFavicon(): ?string
    {
        $favicon = self::get('favicon');
        return $favicon ? asset('storage/' . $favicon) : null;
    }

    /**
     * Get primary color as hex
     */
    public static function getPrimaryColor(): string
    {
        return self::get('primary_color', '#3b82f6');
    }
}
