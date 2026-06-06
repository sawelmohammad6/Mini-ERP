<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalUsers'     => User::count(),
            'totalCustomers' => Customer::count(),
            'totalProducts'  => Product::count(),
            'totalOrders'    => 0,
        ];

        $recentUsers     = User::latest()->take(5)->get();
        $recentCustomers = Customer::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentUsers', 'recentCustomers'));
    }
}
