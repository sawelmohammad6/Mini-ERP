<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function index()
    {
        $query = Expense::query();

        if (request()->filled('category')) {
            $query->where('category', request('category'));
        }

        if (request()->filled('from_date')) {
            $query->whereDate('date', '>=', request('from_date'));
        }

        if (request()->filled('to_date')) {
            $query->whereDate('date', '<=', request('to_date'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where('note', 'like', "%{$search}%");
        }

        $expenses = $query->latest()->paginate(config('erp.pagination_size'));

        $totalExpenses = Expense::sum('amount');
        $currentMonthExpenses = Expense::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
        $expenseCount = Expense::count();

        return view('expenses.index', [
            'expenses'             => $expenses,
            'totalExpenses'        => $totalExpenses,
            'currentMonthExpenses' => $currentMonthExpenses,
            'expenseCount'         => $expenseCount,
            'categories'           => ExpenseCategory::values(),
        ]);
    }

    public function create()
    {
        return view('expenses.create', [
            'categories' => ExpenseCategory::values(),
        ]);
    }

    public function store(StoreExpenseRequest $request)
    {
        try {
            Expense::create($request->validated());
        } catch (\Exception $e) {
            Log::error('Expense creation failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', [
            'expense'    => $expense,
            'categories' => ExpenseCategory::values(),
        ]);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        try {
            $expense->update($request->validated());
        } catch (\Exception $e) {
            Log::error('Expense update failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
        } catch (\Exception $e) {
            Log::error('Expense deletion failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
