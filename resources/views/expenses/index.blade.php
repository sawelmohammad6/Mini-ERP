<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Expense Management</h4>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">+ Add Expense</a>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fce4ec; color: #c62828;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Expenses</div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ format_currency($totalExpenses) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #fef2f2; color: #f46a6a;">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">This Month</div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ format_currency($currentMonthExpenses) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: #f0fdf4; color: #34c38f;">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Entries</div>
                        <div class="stat-value" style="font-size: 1.5rem;">{{ $expenseCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="panel bg-white shadow-sm p-3 mb-4">
        <form method="GET" action="{{ route('expenses.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" placeholder="Search by note..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </form>
    </div>

    <div class="panel bg-white shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Note</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->id }}</td>
                            <td class="fw-medium" style="color: #1a1a2e;">{{ format_currency($expense->amount) }}</td>
                            <td>
                                <span class="badge badge-soft-secondary rounded-pill">{{ $expense->category }}</span>
                            </td>
                            <td style="color: #5a6270; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $expense->note ?: '—' }}
                            </td>
                            <td style="color: #5a6270;">{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this expense?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No expenses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $expenses->links() }}
        </div>
    </div>
</x-app-layout>
