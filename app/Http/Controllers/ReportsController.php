<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function sales(Request $request)
    {
        $query = Order::with('customer');

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->latest()->paginate(10);

        $summary = [];
        $filteredQuery = clone $query;
        $summary['totalOrders'] = $filteredQuery->count();
        $summary['totalSales']  = $filteredQuery->sum('final_price');
        $summary['avgOrderValue'] = $summary['totalOrders'] > 0
            ? $summary['totalSales'] / $summary['totalOrders']
            : 0;

        return view('reports.sales', compact('orders', 'summary'));
    }

    public function expenses(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query->latest()->paginate(10);

        $summary = [];
        $filteredQuery = clone $query;
        $summary['totalExpenses'] = $filteredQuery->sum('amount');
        $summary['highestExpense'] = $filteredQuery->max('amount') ?? 0;
        $summary['expenseCount']   = $filteredQuery->count();

        $categories = [
            'Fuel', 'Salary', 'Office Rent', 'Transport',
            'Maintenance', 'Internet', 'Electricity', 'Other',
        ];

        return view('reports.expenses', compact('expenses', 'summary', 'categories'));
    }
}
