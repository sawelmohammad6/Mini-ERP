<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
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

        $orders = $query->latest()->paginate(config('erp.pagination_size'));

        $filteredQuery = clone $query;
        $summary = [
            'totalOrders'   => $filteredQuery->count(),
            'totalSales'    => $filteredQuery->sum('final_price'),
            'avgOrderValue' => 0,
        ];

        if ($summary['totalOrders'] > 0) {
            $summary['avgOrderValue'] = $summary['totalSales'] / $summary['totalOrders'];
        }

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

        $expenses = $query->latest()->paginate(config('erp.pagination_size'));

        $filteredQuery = clone $query;
        $summary = [
            'totalExpenses'  => $filteredQuery->sum('amount'),
            'highestExpense' => $filteredQuery->max('amount') ?? 0,
            'expenseCount'   => $filteredQuery->count(),
        ];

        return view('reports.expenses', [
            'expenses'   => $expenses,
            'summary'    => $summary,
            'categories' => ExpenseCategory::values(),
        ]);
    }
}
