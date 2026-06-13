<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Expense;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales   = Order::sum('final_price');
        $totalExpenses = Expense::sum('amount');
        $netProfit    = $totalSales - $totalExpenses;

        $stats = [
            'totalSales'       => $totalSales,
            'totalExpenses'    => $totalExpenses,
            'netProfit'        => $netProfit,
            'totalCustomers'   => Customer::count(),
            'totalProducts'    => Product::count(),
            'lowStockProducts' => Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')->count(),
        ];

        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        $labels = [];
        $salesData = [];
        $expenseData = [];

        foreach ($months as $month) {
            [$year, $monthNum] = explode('-', $month);
            $labels[] = \Carbon\Carbon::createFromDate($year, $monthNum, 1)->format('M Y');

            $salesData[] = Order::whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('final_price');

            $expenseData[] = Expense::whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->sum('amount');
        }

        $chartData = [
            'labels'   => $labels,
            'sales'    => $salesData,
            'expenses' => $expenseData,
        ];

        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        $recentExpenses = Expense::latest()
            ->take(5)
            ->get();

        $lowStockProductsList = Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')
            ->get(['name', 'stock_quantity', 'low_stock_alert']);

        $activities = ActivityLog::with('user')
            ->latest()
            ->take(7)
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
