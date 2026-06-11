<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Expense Report</h4>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>Print
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="panel bg-white shadow-sm p-3">
                <div class="text-muted" style="font-size: 0.8rem;">Total Expenses</div>
                <div class="fw-bold" style="font-size: 1.5rem; color: #dc3545;">${{ number_format($summary['totalExpenses'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel bg-white shadow-sm p-3">
                <div class="text-muted" style="font-size: 0.8rem;">Highest Expense</div>
                <div class="fw-bold" style="font-size: 1.5rem; color: #1a1a2e;">${{ number_format($summary['highestExpense'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel bg-white shadow-sm p-3">
                <div class="text-muted" style="font-size: 0.8rem;">Expense Count</div>
                <div class="fw-bold" style="font-size: 1.5rem; color: #556ee6;">{{ $summary['expenseCount'] }}</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="panel bg-white shadow-sm p-4 mb-4">
        <form method="GET" action="{{ route('reports.expenses') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="from_date" class="form-label" style="font-size: 0.85rem;">From Date</label>
                <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label for="to_date" class="form-label" style="font-size: 0.85rem;">To Date</label>
                <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label" style="font-size: 0.85rem;">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.expenses') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="panel bg-white shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Note</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->id }}</td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">{{ $expense->category }}</span>
                            </td>
                            <td class="fw-medium" style="color: #1a1a2e;">${{ number_format($expense->amount, 2) }}</td>
                            <td style="color: #5a6270;">{{ Str::limit($expense->note, 40) ?? '—' }}</td>
                            <td style="color: #a4b0c2;">{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No expenses found for the selected filters.</td>
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

<style media="print">
    .sidebar, .topbar, .btn, form, .pagination, .alert { display: none !important; }
    .panel { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
</style>
