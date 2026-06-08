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

    {{-- Stats Cards --}}
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

    <div class="row g-3 mb-4">
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
        <div class="col-xl-8">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-0" style="color: #1a1a2e;">Revenue Overview</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Monthly revenue chart placeholder</p>
                <div class="chart-placeholder">
                    <div class="text-center">
                        <i class="bi bi-bar-chart-fill fs-1 d-block mb-2"></i>
                        <span>Chart will render here</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="panel bg-white shadow-sm p-4">
                <h6 class="fw-semibold mb-0" style="color: #1a1a2e;">Sales by Category</h6>
                <p class="text-muted mb-3" style="font-size: 0.8rem;">Donut chart placeholder</p>
                <div class="chart-placeholder">
                    <div class="text-center">
                        <i class="bi bi-pie-chart-fill fs-1 d-block mb-2"></i>
                        <span>Chart will render here</span>
                    </div>
                </div>
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
