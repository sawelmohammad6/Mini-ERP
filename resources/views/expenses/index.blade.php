<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Expense Management</h4>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">+ Add Expense</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="panel bg-white shadow-sm p-4 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: #fefce8; color: #f1b44c; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-cash-stack fs-5"></i>
            </div>
            <div>
                <div class="text-muted" style="font-size: 0.8rem;">Total Expenses</div>
                <div class="fw-bold" style="font-size: 1.5rem; color: #1a1a2e;">{{ format_currency($totalExpenses) }}</div>
            </div>
        </div>
    </div>

    <div class="panel bg-white shadow-sm p-4 mb-4">
        <form method="GET" action="{{ route('expenses.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="category" class="form-label" style="font-size: 0.85rem;">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="from_date" class="form-label" style="font-size: 0.85rem;">From Date</label>
                <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label" style="font-size: 0.85rem;">To Date</label>
                <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
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
                                <span class="badge bg-secondary rounded-pill">{{ $expense->category }}</span>
                            </td>
                            <td style="color: #5a6270;">{{ Str::limit($expense->note, 40) ?? '—' }}</td>
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
