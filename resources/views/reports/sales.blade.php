<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #1a1a2e;">Sales Report</h4>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>Print
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="panel bg-white shadow-sm p-3">
                <div class="text-muted" style="font-size: 0.8rem;">Total Orders</div>
                <div class="fw-bold" style="font-size: 1.5rem; color: #1a1a2e;">{{ $summary['totalOrders'] }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel bg-white shadow-sm p-3">
                <div class="text-muted" style="font-size: 0.8rem;">Total Sales</div>
                <div class="fw-bold" style="font-size: 1.5rem; color: #34c38f;">{{ format_currency($summary['totalSales']) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel bg-white shadow-sm p-3">
                <div class="text-muted" style="font-size: 0.8rem;">Average Order Value</div>
                <div class="fw-bold" style="font-size: 1.5rem; color: #556ee6;">{{ format_currency($summary['avgOrderValue']) }}</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="panel bg-white shadow-sm p-4 mb-4">
        <form method="GET" action="{{ route('reports.sales') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="from_date" class="form-label" style="font-size: 0.85rem;">From Date</label>
                <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-4">
                <label for="to_date" class="form-label" style="font-size: 0.85rem;">To Date</label>
                <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('reports.sales') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="panel bg-white shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Final Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-medium" style="color: #1a1a2e;">#{{ $order->id }}</td>
                            <td style="color: #5a6270;">{{ $order->customer->name }}</td>
                            <td style="color: #5a6270;">{{ format_currency($order->total) }}</td>
                            <td style="color: #5a6270;">{{ format_currency($order->discount) }}</td>
                            <td style="color: #1a1a2e; font-weight: 600;">{{ format_currency($order->final_price) }}</td>
                            <td style="color: #a4b0c2;">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No orders found for the selected period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

<style media="print">
    .sidebar, .topbar, .btn, form, .pagination, .alert { display: none !important; }
    .panel { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
</style>
