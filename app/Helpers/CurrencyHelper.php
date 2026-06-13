<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function symbol(string $currency = null): string
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

    public static function format($amount, string $currency = null): string
    {
        return self::symbol($currency) . number_format((float) $amount, 2);
    }
}
