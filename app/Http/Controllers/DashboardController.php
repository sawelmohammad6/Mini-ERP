<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales   = Order::sum('final_price');
        $totalExpenses = Expense::sum('amount');
        $netProfit    = $totalSales - $totalExpenses;

        $stats = [
            'totalUsers'        => User::count(),
            'totalCustomers'    => Customer::count(),
            'totalProducts'     => Product::count(),
            'totalOrders'       => Order::count(),
            'totalSales'        => $totalSales,
            'totalExpenses'     => $totalExpenses,
            'netProfit'         => $netProfit,
            'lowStockProducts'  => Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')->count(),
        ];

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        $salesData = [];
        $expenseData = [];
        $labels = [];

        foreach ($months as $month) {
            $year  = substr($month, 0, 4);
            $monthNum = substr($month, 5, 2);
            $labels[] = \Carbon\Carbon::createFromDate($year, $monthNum, 1)->format('M Y');

            $salesData[] = Order::whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('final_price');

            $expenseData[] = Expense::whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->sum('amount');
        }

        $chartData = [
            'labels'  => $labels,
            'sales'   => $salesData,
            'expenses' => $expenseData,
        ];

        $recentUsers     = User::latest()->take(5)->get();
        $recentCustomers = Customer::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentUsers', 'recentCustomers', 'chartData'));
    }
}
