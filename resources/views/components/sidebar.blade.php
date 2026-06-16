<div class="sidebar">
    <div class="brand">
        @if ($setting && $setting->logo)
            <img src="{{ Storage::url($setting->logo) }}" alt="Logo" style="height: 32px; border-radius: 6px;">
        @else
            <i class="bi bi-grid-3x3-gap-fill"></i>
        @endif
        <span>{{ $setting->business_name ?? 'Mini ERP' }}</span>
    </div>

    <div class="nav">
        <div class="nav-label">Menu</div>

        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> User Management
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Customer Management
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Product Management
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-fill"></i> Orders
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Expense Management
            </a>
        </div>

        <div class="nav-label">Reports</div>
        <div class="nav-item">
            <a href="{{ route('reports.sales') }}" class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Sales Report
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('reports.expenses') }}" class="nav-link {{ request()->routeIs('reports.expenses') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Expense Report
            </a>
        </div>

        <div class="nav-label">System</div>
        <div class="nav-item">
            <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Activity Logs
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Settings
            </a>
        </div>
    </div>

    <div class="sidebar-footer">
        &copy; {{ date('Y') }} {{ $setting->business_name ?? 'Mini ERP' }} v1.0
    </div>
</div>
