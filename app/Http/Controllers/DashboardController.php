<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Expense;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales    = Order::sum('final_price');
        $totalExpenses = Expense::sum('amount');
        $netProfit     = $totalSales - $totalExpenses;

        $stats = [
            'totalSales'       => $totalSales,
            'totalExpenses'    => $totalExpenses,
            'netProfit'        => $netProfit,
            'totalCustomers'   => Customer::count(),
            'totalProducts'    => Product::count(),
            'lowStockProducts' => Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')->count(),
        ];

        $chartMonths = config('erp.dashboard.chart_months');
        $labels      = [];
        $salesData   = [];
        $expenseData = [];

        for ($i = $chartMonths - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $salesData[] = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('final_price');

            $expenseData[] = Expense::whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('amount');
        }

        $chartData = [
            'labels'   => $labels,
            'sales'    => $salesData,
            'expenses' => $expenseData,
        ];

        $recentOrders = Order::with('customer')
            ->latest()
            ->take(config('erp.dashboard.recent_orders_count'))
            ->get();

        $recentExpenses = Expense::latest()
            ->take(config('erp.dashboard.recent_expenses_count'))
            ->get();

        $lowStockProductsList = Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')
            ->get(['name', 'stock_quantity', 'low_stock_alert']);

        $activities = ActivityLog::with('user')
            ->latest()
            ->take(config('erp.dashboard.activities_count'))
            ->get();

        return view('dashboard', compact(
            'stats',
            'chartData',
            'recentOrders',
            'recentExpenses',
            'lowStockProductsList',
            'activities'
        ));
    }
}
