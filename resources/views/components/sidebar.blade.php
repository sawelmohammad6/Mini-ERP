<div class="sidebar">
    <div class="brand">
        <i class="bi bi-grid-3x3-gap-fill"></i>
        <span>Mini ERP</span>
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

        <div class="nav-item">
            <a href="#" class="nav-link disabled">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Reports
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link disabled">
                <i class="bi bi-gear-fill"></i> Settings
            </a>
        </div>
    </div>

    <div class="sidebar-footer">
        &copy; {{ date('Y') }} Mini ERP v1.0
    </div>
</div>
