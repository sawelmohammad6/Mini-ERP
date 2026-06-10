<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $expenses = $query->latest()->paginate(10);

        $totalExpenses = Expense::sum('amount');

        $categories = [
            'Fuel', 'Salary', 'Office Rent', 'Transport',
            'Maintenance', 'Internet', 'Electricity', 'Other',
        ];

        return view('expenses.index', compact('expenses', 'totalExpenses', 'categories'));
    }

    public function create()
    {
        $categories = [
            'Fuel', 'Salary', 'Office Rent', 'Transport',
            'Maintenance', 'Internet', 'Electricity', 'Other',
        ];

        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount'   => 'required|numeric|min:0',
            'category' => 'required|string',
            'note'     => 'nullable|string',
            'date'     => 'required|date',
        ]);

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    public function edit(Expense $expense)
    {
        $categories = [
            'Fuel', 'Salary', 'Office Rent', 'Transport',
            'Maintenance', 'Internet', 'Electricity', 'Other',
        ];

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'amount'   => 'required|numeric|min:0',
            'category' => 'required|string',
            'note'     => 'nullable|string',
            'date'     => 'required|date',
        ]);

        $expense->update($validated);

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
