<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Expense;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Dashboard data load failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            $stats = [
                'totalSales'       => 0,
                'totalExpenses'    => 0,
                'netProfit'        => 0,
                'totalCustomers'   => 0,
                'totalProducts'    => 0,
                'lowStockProducts' => 0,
            ];

            $chartData = ['labels' => [], 'sales' => [], 'expenses' => []];
            $recentOrders = collect();
            $recentExpenses = collect();
            $lowStockProductsList = collect();
            $activities = collect();

            return view('dashboard', compact(
                'stats', 'chartData', 'recentOrders',
                'recentExpenses', 'lowStockProductsList', 'activities'
            ))->with('warning', 'Unable to load all dashboard data.');
        }

        return view('dashboard', compact(
            'stats', 'chartData', 'recentOrders',
            'recentExpenses', 'lowStockProductsList', 'activities'
        ));
    }
}
