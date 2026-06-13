<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting($key = null)
    {
        static $setting = null;
        if ($setting === null) {
            $setting = Setting::first() ?? new Setting();
        }
        if ($key === null) {
            return $setting;
        }
        return $setting->$key ?? null;
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol($currency = null)
    {
        $symbols = [
            'BDT' => '৳',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'INR' => '₹',
        ];
        $currency = $currency ?? (setting('currency') ?? 'USD');
        return $symbols[$currency] ?? '$';
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amount, $currency = null)
    {
        return currency_symbol($currency) . number_format((float) $amount, 2);
    }
}
