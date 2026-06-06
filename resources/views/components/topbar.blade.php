<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="hamburger" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search anything...">
        </div>
    </div>

    <div class="topbar-actions">
        <button class="btn-icon" title="Notifications">
            <i class="bi bi-bell-fill"></i>
            <span class="badge-dot"></span>
        </button>
        <button class="btn-icon" title="Fullscreen" onclick="document.documentElement.requestFullscreen?.()">
            <i class="bi bi-arrows-fullscreen"></i>
        </button>

        <div class="dropdown">
            <div class="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-info d-none d-sm-block">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border: none; border-radius: 12px; min-width: 200px;">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2"></i>Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Log Out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
