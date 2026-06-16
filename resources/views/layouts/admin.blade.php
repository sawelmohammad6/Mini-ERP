<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting->business_name ?? config('app.name', 'Mini ERP') }} @isset($title) — {{ $title }} @endisset</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        body { background: #f5f6fa; overflow-x: hidden; }
        .wrapper { display: flex; min-height: 100vh; }

        .sidebar {
            width: 250px; min-width: 250px;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            color: #a4b0c2;
            display: flex; flex-direction: column;
            transition: all 0.3s ease; z-index: 1040;
        }
        .sidebar .brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.35rem; font-weight: 700;
            color: #fff; letter-spacing: -0.5px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar .brand i { font-size: 1.5rem; color: #556ee6; }
        .sidebar .nav { flex: 1; overflow-y: auto; padding: 0.1rem 0; display: block; }
        .sidebar .nav-item { padding: 0 0.75rem; margin-bottom: 0; }
        .sidebar .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 16px; margin-bottom: 2px;
            color: #a4b0c2; text-decoration: none; font-size: 0.9rem;
            border-radius: 8px; transition: all 0.2s ease;
        }
        .sidebar .nav-link i { font-size: 1rem; width: 20px; text-align: center; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.06); color: #fff; }
        .sidebar .nav-link.active {
            background: #556ee6; color: #fff; font-weight: 500;
            box-shadow: 0 3px 12px rgba(85,110,230,.4);
        }
        .sidebar .nav-label {
            padding: 0.2rem 1.5rem 0.05rem;
            font-size: 0.6rem; text-transform: uppercase; letter-spacing: 1px;
            color: rgba(164,176,194,.5);
        }
        .sidebar .nav-link-sub {
            padding-left: 3rem !important;
            font-size: 0.82rem !important;
        }
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.06); font-size: 0.75rem;
            color: rgba(164,176,194,.4);
        }

        .topbar {
            background: #fff; padding: 0 1.5rem; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #eef0f5;
            position: sticky; top: 0; z-index: 1030;
        }
        .topbar .search-box {
            display: flex; align-items: center;
            background: #f5f6fa; border-radius: 8px; padding: 0 1rem;
            width: 320px;
        }
        .topbar .search-box i { color: #a4b0c2; font-size: 1rem; }
        .topbar .search-box input {
            border: none; background: transparent;
            padding: 0.55rem 0.75rem; font-size: 0.875rem; width: 100%;
            outline: none;
        }
        .topbar .topbar-actions {
            display: flex; align-items: center; gap: 1.25rem;
        }
        .topbar .topbar-actions .btn-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: #f5f6fa; color: #5a6270; border: none; font-size: 1.15rem;
            transition: all 0.2s; cursor: pointer; position: relative;
        }
        .topbar .topbar-actions .btn-icon:hover { background: #eef0f5; color: #1a1a2e; }
        .topbar .topbar-actions .btn-icon .badge-dot {
            position: absolute; top: 6px; right: 6px;
            width: 8px; height: 8px; background: #f46a6a; border-radius: 50%;
            border: 2px solid #fff;
        }
        .topbar .user-dropdown {
            display: flex; align-items: center; gap: 10px; cursor: pointer;
            padding: 6px 12px; border-radius: 10px; transition: background 0.2s;
        }
        .topbar .user-dropdown:hover { background: #f5f6fa; }
        .topbar .user-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: #556ee6; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 0.85rem;
        }
        .topbar .user-info .name { font-size: 0.85rem; font-weight: 600; color: #1a1a2e; }
        .topbar .user-info .role { font-size: 0.7rem; color: #a4b0c2; }

        .main-content {
            flex: 1; display: flex; flex-direction: column; min-width: 0;
        }
        .page-content { flex: 1; padding: 1.5rem; }

        .hamburger {
            display: none; border: none; background: none; font-size: 1.5rem;
            color: #5a6270; cursor: pointer; padding: 4px;
        }

        .stat-card {
            border: none; border-radius: 14px; padding: 1.25rem 1.5rem;
            overflow: hidden; position: relative; height: 100%;
        }
        .stat-card .stat-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }
        .stat-card .stat-title { font-size: 0.8rem; color: #a4b0c2; font-weight: 500; }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 700; color: #1a1a2e; }
        .stat-card .stat-footer { font-size: 0.75rem; color: #a4b0c2; }

        .panel { border: none; border-radius: 14px; overflow: hidden; }

        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4);
            z-index: 1035;
        }

        .activity-item {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 8px 0; border-bottom: 1px solid #f1f3f5;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-badge {
            width: 28px; height: 28px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 0.7rem;
        }
        .activity-badge.created { background: #e8f5e9; color: #2e7d32; }
        .activity-badge.updated { background: #fff3e0; color: #e65100; }
        .activity-badge.deleted { background: #fce4ec; color: #c62828; }

        .welcome-card {
            position: relative; border-radius: 16px; overflow: hidden;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 1.75rem 2rem;
        }
        .welcome-card .welcome-content {
            position: relative; z-index: 1;
            display: flex; align-items: center; justify-content: space-between;
            color: #fff;
        }
        .welcome-card .welcome-sub { color: rgba(255,255,255,.6); font-size: 0.85rem; }
        .welcome-card .welcome-badge {
            background: rgba(255,255,255,.1); color: #fff;
            padding: 6px 16px; border-radius: 20px; font-size: 0.8rem;
            backdrop-filter: blur(4px);
        }

        .table > :not(caption) > * > * {
            padding: 0.7rem 0.75rem;
            vertical-align: middle;
        }
        .table-hover > tbody > tr:hover {
            background-color: rgba(85,110,230,.04);
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,.015);
        }

        .badge-soft-secondary {
            background: #f1f3f5; color: #5a6270; font-weight: 500;
        }

        .toast-container {
            position: fixed; top: 1rem; right: 1rem; z-index: 9999;
        }

        @media (max-width: 991.98px) {
            .sidebar { position: fixed; left: -260px; height: 100%; z-index: 1045; transition: left 0.3s ease; }
            .sidebar.open { left: 0; }
            .sidebar-overlay.open { display: block; }
            .hamburger { display: block; }
            .topbar .search-box { width: 180px; }
        }
        @media (max-width: 575.98px) {
            .topbar .search-box { display: none; }
            .topbar .user-info { display: none; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    @include('components.sidebar')

    <div class="main-content">
        @include('components.topbar')

        <div class="page-content">
            {{ $slot }}
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if (session('error'))
            showToast(@json(session('error')), 'danger');
        @endif
        @if (session('warning'))
            showToast(@json(session('warning')), 'warning');
        @endif
        @if (session('info'))
            showToast(@json(session('info')), 'info');
        @endif
    });

    function showToast(message, type) {
        var container = document.getElementById('toastContainer');
        var colors = {
            success: { bg: '#198754', icon: 'bi-check-circle-fill' },
            danger:  { bg: '#dc3545', icon: 'bi-exclamation-circle-fill' },
            warning: { bg: '#f1b44c', icon: 'bi-exclamation-triangle-fill' },
            info:    { bg: '#556ee6', icon: 'bi-info-circle-fill' },
        };
        var c = colors[type] || colors.info;
        var html = '<div class="toast align-items-center text-white border-0 show" role="alert" style="background: ' + c.bg + '; border-radius: 10px;">' +
            '<div class="d-flex">' +
            '<div class="toast-body d-flex align-items-center gap-2" style="font-size: 0.9rem;">' +
            '<i class="bi ' + c.icon + '"></i> ' + message +
            '</div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
            '</div>' +
            '</div>';
        container.insertAdjacentHTML('beforeend', html);
        var el = container.lastElementChild;
        setTimeout(function () {
            el.classList.remove('show');
            setTimeout(function () { el.remove(); }, 300);
        }, 4000);
    }
</script>
@stack('scripts')
</body>
</html>
