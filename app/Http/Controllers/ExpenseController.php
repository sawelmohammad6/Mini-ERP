<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;

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
        Expense::create($request->validated());

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
        $expense->update($request->validated());

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
