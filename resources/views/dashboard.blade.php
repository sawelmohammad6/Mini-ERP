<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="welcome-card mb-4">
        <div class="welcome-bg"></div>
        <div class="welcome-content">
            <div>
                <h4 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}</h4>
                <p class="mb-0 welcome-sub">
                    {{ $setting->business_name ?? 'Mini ERP' }} &middot; {{ now()->format('l, F d, Y') }}
                </p>
            </div>
            <div class="d-none d-sm-block">
                <span class="welcome-badge">
                    <i class="bi bi-calendar3 me-1"></i> {{ now()->format('M d, Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Sales</div>
                        <div class="stat-value" style="font-size: 1rem;">{{ format_currency($stats['totalSales']) }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-cart-check text-success me-1"></i> Revenue
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #fce4ec; color: #c62828;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Expenses</div>
                        <div class="stat-value" style="font-size: 1rem;">{{ format_currency($stats['totalExpenses']) }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-arrow-down text-danger me-1"></i> Costs
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="bi bi-piggy-bank-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Net Profit</div>
                        <div class="stat-value" style="font-size: 1rem; {{ $stats['netProfit'] < 0 ? 'color: #dc3545;' : 'color: #198754;' }}">
                            {{ format_currency($stats['netProfit']) }}
                        </div>
                    </div>
                </div>
                <div class="stat-footer">
                    @if ($stats['netProfit'] >= 0)
                        <i class="bi bi-arrow-up text-success me-1"></i> Positive
                    @else
                        <i class="bi bi-arrow-down text-danger me-1"></i> Negative
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #fef2f2; color: #f46a6a;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Customers</div>
                        <div class="stat-value" style="font-size: 1rem;">{{ $stats['totalCustomers'] }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-people text-success me-1"></i> Active
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #f0fdf4; color: #34c38f;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Products</div>
                        <div class="stat-value" style="font-size: 1rem;">{{ $stats['totalProducts'] }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-box text-success me-1"></i> Inventory
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #fef2f2; color: #ef4444;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Low Stock</div>
                        <div class="stat-value" style="font-size: 1rem;">{{ $stats['lowStockProducts'] }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-arrow-down text-danger me-1"></i> Needs attention
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-12">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-0" style="color: #1a1a2e;">Sales vs Expenses Comparison</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Monthly comparison — last 12 months</p>
                <canvas id="comparisonChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-0" style="color: #1a1a2e;">Monthly Sales</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Last 12 months</p>
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-0" style="color: #1a1a2e;">Monthly Expenses</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Last 12 months</p>
                <canvas id="expensesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Tables Section --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-4">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-3" style="color: #1a1a2e;">
                    <i class="bi bi-cart-fill me-2"></i>Recent Orders
                </h6>
                @if ($recentOrders->count())
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                            <thead style="color: #a4b0c2; font-size: 0.75rem;">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentOrders as $o)
                                    <tr>
                                        <td class="fw-medium" style="color: #1a1a2e;">#{{ $o->id }}</td>
                                        <td style="color: #5a6270;">{{ $o->customer->name }}</td>
                                        <td style="color: #1a1a2e;">{{ format_currency($o->final_price) }}</td>
                                        <td style="color: #a4b0c2;">{{ $o->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state icon="bi-cart" message="No orders yet." />
                @endif
            </div>
        </div>

        <div class="col-xl-4">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-3" style="color: #1a1a2e;">
                    <i class="bi bi-cash-stack me-2"></i>Recent Expenses
                </h6>
                @if ($recentExpenses->count())
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                            <thead style="color: #a4b0c2; font-size: 0.75rem;">
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentExpenses as $e)
                                    <tr>
                                        <td>
                                            <span class="badge badge-soft-secondary rounded-pill">{{ $e->category }}</span>
                                        </td>
                                        <td style="color: #1a1a2e;">{{ format_currency($e->amount) }}</td>
                                        <td style="color: #a4b0c2;">{{ \Carbon\Carbon::parse($e->date)->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state icon="bi-cash-stack" message="No expenses yet." />
                @endif
            </div>
        </div>

        <div class="col-xl-4">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-3" style="color: #1a1a2e;">
                    <i class="bi bi-clock-history me-2" style="color: #556ee6;"></i>Recent Activities
                </h6>
                @if ($activities->count())
                    @foreach ($activities as $activity)
                        <div class="activity-item">
                            <div class="activity-badge {{ $activity->action }}">
                                @if ($activity->action === 'created')
                                    <i class="bi bi-plus-lg"></i>
                                @elseif ($activity->action === 'updated')
                                    <i class="bi bi-pencil"></i>
                                @elseif ($activity->action === 'deleted')
                                    <i class="bi bi-trash3"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div style="font-size: 0.8rem; color: #1a1a2e; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $activity->description }}
                                </div>
                                <div style="font-size: 0.65rem; color: #a4b0c2;">
                                    {{ $activity->user->name ?? 'System' }} &middot; {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <x-empty-state icon="bi-clock-history" message="No activities yet." />
                @endif
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-12">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-3" style="color: #1a1a2e;">
                    <i class="bi bi-exclamation-triangle-fill me-2" style="color: #ef4444;"></i>Low Stock Products
                </h6>
                @if ($lowStockProductsList->count())
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                            <thead style="color: #a4b0c2; font-size: 0.75rem;">
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Alert</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lowStockProductsList as $p)
                                    <tr>
                                        <td style="color: #1a1a2e;">{{ $p->name }}</td>
                                        <td>
                                            <span class="badge bg-danger rounded-pill">{{ $p->stock_quantity }}</span>
                                        </td>
                                        <td style="color: #a4b0c2;">{{ $p->low_stock_alert }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state icon="bi-check-circle" message="All products well stocked." />
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = @json($chartData['labels']);
        const salesData = @json($chartData['sales']);
        const expenseData = @json($chartData['expenses']);
        const currencySymbol = '{{ currency_symbol() }}';

        const sharedOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { boxWidth: 12, padding: 16, font: { size: 11 } },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: function (v) { return currencySymbol + v.toFixed(0); } },
                    grid: { color: '#f1f3f5' },
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } },
                },
            },
        };

        new Chart(document.getElementById('comparisonChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Sales',
                        data: salesData,
                        backgroundColor: 'rgba(52, 195, 143, 0.7)',
                        borderColor: '#34c38f',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'Expenses',
                        data: expenseData,
                        backgroundColor: 'rgba(244, 106, 106, 0.7)',
                        borderColor: '#f46a6a',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                ],
            },
            options: sharedOptions,
        });

        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales',
                    data: salesData,
                    backgroundColor: 'rgba(52, 195, 143, 0.1)',
                    borderColor: '#34c38f',
                    borderWidth: 3,
                    pointBackgroundColor: '#34c38f',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.35,
                }],
            },
            options: sharedOptions,
        });

        new Chart(document.getElementById('expensesChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Expenses',
                    data: expenseData,
                    backgroundColor: 'rgba(244, 106, 106, 0.1)',
                    borderColor: '#f46a6a',
                    borderWidth: 3,
                    pointBackgroundColor: '#f46a6a',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.35,
                }],
            },
            options: sharedOptions,
        });
    });
</script>
@endpush
