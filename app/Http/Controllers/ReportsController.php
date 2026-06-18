<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    public function sales(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Sales report loading failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            $orders = collect();
            $summary = [
                'totalOrders'   => 0,
                'totalSales'    => 0,
                'avgOrderValue' => 0,
            ];

            return view('reports.sales', compact('orders', 'summary'))
                ->with('warning', 'Unable to load sales report data.');
        }

        return view('reports.sales', compact('orders', 'summary'));
    }

    public function expenses(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Expense report loading failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            $expenses = collect();
            $summary = [
                'totalExpenses'  => 0,
                'highestExpense' => 0,
                'expenseCount'   => 0,
            ];

            return view('reports.expenses', [
                'expenses'   => $expenses,
                'summary'    => $summary,
                'categories' => ExpenseCategory::values(),
            ])->with('warning', 'Unable to load expense report data.');
        }

        return view('reports.expenses', [
            'expenses'   => $expenses,
            'summary'    => $summary,
            'categories' => ExpenseCategory::values(),
        ]);
    }
}
