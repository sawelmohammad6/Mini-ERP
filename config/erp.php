<?php

/**
 * ERP System Configuration
 *
 * Why config values instead of hardcoded magic numbers?
 * ------------------------------------------------------
 * 1. Centralized — All tunable constants live in one file. No hunting
 *    through controllers for magic numbers.
 * 2. Environment-aware — Config can reference .env variables for
 *    per-environment overrides.
 * 3. Testable — Tests can override config() values without touching code.
 * 4. Documented — Each value has a clear comment explaining its purpose.
 *
 * When to add a value here vs a database setting?
 * -------------------------------------------------
 * Technical defaults (pagination size, limits) belong here.
 * Business data (currency, business name) belongs in the 'settings' table
 * managed through the Settings UI.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    | Default number of records per page across all resource listings.
    */
    'pagination_size' => env('ERP_PAGINATION_SIZE', 10),

    /*
    |--------------------------------------------------------------------------
    | Stock & Inventory
    |--------------------------------------------------------------------------
    | Thresholds used for low-stock alerts and inventory warnings.
    */
    'low_stock_limit' => env('ERP_LOW_STOCK_LIMIT', 5),

    /*
    |--------------------------------------------------------------------------
    | Dashboard Limits
    |--------------------------------------------------------------------------
    | Number of items shown in each dashboard widget.
    */
    'dashboard' => [
        'recent_orders_count'   => env('ERP_DASHBOARD_RECENT_ORDERS', 5),
        'recent_expenses_count' => env('ERP_DASHBOARD_RECENT_EXPENSES', 5),
        'activities_count'      => env('ERP_DASHBOARD_ACTIVITIES', 7),
        'chart_months'          => env('ERP_DASHBOARD_CHART_MONTHS', 12),
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    | Fallback default used when no Setting record exists yet.
    */
    'default_currency' => env('ERP_DEFAULT_CURRENCY', 'USD'),

];
