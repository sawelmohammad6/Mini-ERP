<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1a1a2e;">Dashboard</h4>
            <p class="mb-0" style="color: #a4b0c2; font-size: 0.875rem;">
                Welcome back, {{ Auth::user()->name }}
            </p>
        </div>
        <div>
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('M d, Y') }}
            </span>
        </div>
    </div>

    {{-- Stats Row 1 — Business Metrics --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #eef2ff; color: #556ee6;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Users</div>
                        <div class="stat-value">{{ $stats['totalUsers'] }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-arrow-up text-success me-1"></i> Registered accounts
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #fef2f2; color: #f46a6a;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Customers</div>
                        <div class="stat-value">{{ $stats['totalCustomers'] }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-arrow-up text-success me-1"></i> Active clients
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #f0fdf4; color: #34c38f;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Products</div>
                        <div class="stat-value">{{ $stats['totalProducts'] }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-arrow-up text-success me-1"></i> In inventory
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #fefce8; color: #f1b44c;">
                        <i class="bi bi-cart-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Orders</div>
                        <div class="stat-value">{{ $stats['totalOrders'] }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-check-circle text-success me-1"></i> Orders placed
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row 2 — Financial Metrics --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Sales</div>
                        <div class="stat-value" style="font-size: 1.2rem;">${{ number_format($stats['totalSales'], 2) }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-cart-check text-success me-1"></i> Revenue generated
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #fce4ec; color: #c62828;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="stat-title">Total Expenses</div>
                        <div class="stat-value" style="font-size: 1.2rem;">${{ number_format($stats['totalExpenses'], 2) }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="bi bi-arrow-down text-danger me-1"></i> Costs incurred
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="bi bi-piggy-bank-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Net Profit</div>
                        <div class="stat-value" style="font-size: 1.2rem; {{ $stats['netProfit'] < 0 ? 'color: #dc3545;' : 'color: #198754;' }}">
                            ${{ number_format($stats['netProfit'], 2) }}
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

        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #fef2f2; color: #ef4444;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-title">Low Stock Products</div>
                        <div class="stat-value">{{ $stats['lowStockProducts'] }}</div>
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
                <h6 class="fw-semibold mb-0" style="color: #1a1a2e;">Sales vs Expenses</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Monthly comparison for the last 6 months</p>
                <canvas id="salesExpensesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row g-3">
        <div class="col-xl-6">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-3" style="color: #1a1a2e;">
                    <i class="bi bi-clock-history me-2"></i>Recent Users
                </h6>
                @if ($recentUsers->count())
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                            <thead style="color: #a4b0c2; font-size: 0.75rem;">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentUsers as $u)
                                    <tr>
                                        <td class="fw-medium" style="color: #1a1a2e;">{{ $u->name }}</td>
                                        <td style="color: #5a6270;">{{ $u->email }}</td>
                                        <td>
                                            <span class="badge {{ $u->role === 'admin' ? 'bg-danger' : 'bg-secondary' }} rounded-pill">
                                                {{ ucfirst($u->role) }}
                                            </span>
                                        </td>
                                        <td style="color: #a4b0c2;">{{ $u->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">No users yet.</p>
                @endif
            </div>
        </div>

        <div class="col-xl-6">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-3" style="color: #1a1a2e;">
                    <i class="bi bi-person-lines-fill me-2"></i>Recent Customers
                </h6>
                @if ($recentCustomers->count())
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                            <thead style="color: #a4b0c2; font-size: 0.75rem;">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentCustomers as $c)
                                    <tr>
                                        <td class="fw-medium" style="color: #1a1a2e;">{{ $c->name }}</td>
                                        <td style="color: #5a6270;">{{ $c->phone }}</td>
                                        <td style="color: #5a6270;">{{ $c->email ?? '—' }}</td>
                                        <td style="color: #a4b0c2;">{{ $c->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">No customers yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesExpensesChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Sales',
                        data: @json($chartData['sales']),
                        backgroundColor: 'rgba(52, 195, 143, 0.7)',
                        borderColor: '#34c38f',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'Expenses',
                        data: @json($chartData['expenses']),
                        backgroundColor: 'rgba(244, 106, 106, 0.7)',
                        borderColor: '#f46a6a',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 16,
                            font: { size: 12 },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) { return '$' + value.toFixed(0); },
                        },
                        grid: { color: '#f1f3f5' },
                    },
                    x: {
                        grid: { display: false },
                    },
                },
            },
        });
    });
</script>
@endpush
