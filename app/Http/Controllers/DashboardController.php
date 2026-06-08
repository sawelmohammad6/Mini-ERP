<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalUsers'        => User::count(),
            'totalCustomers'    => Customer::count(),
            'totalProducts'     => Product::count(),
            'totalOrders'       => Order::count(),
            'lowStockProducts'  => Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')->count(),
        ];

        $recentUsers     = User::latest()->take(5)->get();
        $recentCustomers = Customer::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentUsers', 'recentCustomers'));
    }
}
