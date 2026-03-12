<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>
            <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');

        :root {
            color-scheme: light;
            --bg: #f4f2ee;
            --panel: #ffffff;
            --panel-strong: #f9f6f2;
            --ink: #1f2328;
            --muted: #6b7280;
            --accent: #0f766e;
            --accent-2: #f59e0b;
            --border: #e7e2db;
            --shadow: 0 24px 60px rgba(31, 35, 40, 0.12);
            --radius: 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Space Grotesk", "Segoe UI", system-ui, sans-serif;
            color: var(--ink);
            background: radial-gradient(circle at 10% 10%, rgba(245, 158, 11, 0.12), transparent 45%),
                        radial-gradient(circle at 90% 20%, rgba(15, 118, 110, 0.12), transparent 50%),
                        var(--bg);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .admin-shell {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .admin-sidebar {
            padding: 32px 24px;
            background: var(--panel);
            border-right: 1px solid var(--border);
        }

        .brand {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            margin-bottom: 28px;
        }

        .nav-group {
            display: grid;
            gap: 10px;
        }

        .nav-link {
            padding: 10px 14px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
            color: var(--muted);
            font-weight: 500;
        }

        .nav-link.active {
            background: rgba(15, 118, 110, 0.12);
            color: var(--accent);
        }

        .subnav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 0 0 20px;
        }

        .subnav-link,
        .subnav-pill {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            background: var(--panel);
            border: 1px solid var(--border);
        }

        .subnav-link.active {
            color: var(--accent);
            border-color: rgba(15, 118, 110, 0.4);
            background: rgba(15, 118, 110, 0.08);
        }

        .nav-link:hover,
        .nav-link:focus {
            background: var(--panel-strong);
            color: var(--ink);
        }

        .admin-main {
            padding: 16px 40px 48px;
        }

        .page-title {
            font-size: 1.8rem;
            margin: 0 0 6px;
            font-weight: 700;
        }

        .page-subtitle {
            color: var(--muted);
            margin: 0 0 24px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .admin-topbar {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 18px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
        }

        .admin-topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .admin-topbar-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .admin-topbar-logo {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.02em;
        }

        .admin-topbar-logo-mark {
            color: var(--accent);
        }

        .admin-topbar-nav {
            display: flex;
            gap: 12px;
        }

        .admin-topbar-link {
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            color: var(--muted);
            border: 1px solid transparent;
        }

        .admin-topbar-link.active,
        .admin-topbar-link:hover {
            color: var(--accent);
            border-color: rgba(15, 118, 110, 0.3);
            background: rgba(15, 118, 110, 0.08);
        }

        .admin-topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-topbar-menu {
            position: relative;
        }

        .admin-topbar-trigger {
            list-style: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid var(--border);
            color: var(--muted);
            font-weight: 600;
        }

        .admin-topbar-trigger::-webkit-details-marker {
            display: none;
        }

        .admin-topbar-caret {
            width: 16px;
            height: 16px;
        }

        .admin-topbar-menu[open] .admin-topbar-trigger {
            color: var(--accent);
            border-color: rgba(15, 118, 110, 0.3);
            background: rgba(15, 118, 110, 0.08);
        }

        .admin-topbar-menu-items {
            position: absolute;
            right: 0;
            margin-top: 8px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px;
            min-width: 160px;
            display: grid;
            gap: 8px;
            box-shadow: var(--shadow);
            z-index: 20;
        }

        .admin-topbar-menu-items a,
        .admin-topbar-menu-items button {
            text-align: left;
            font-weight: 600;
            color: var(--ink);
            background: transparent;
            border: none;
            padding: 6px 8px;
            border-radius: 2px;
            cursor: pointer;
        }

        .admin-topbar-menu-items a:hover,
        .admin-topbar-menu-items button:hover {
            background: var(--panel-strong);
        }

        .admin-topbar-meta {
            margin-top: 16px;
        }

        .card {
            background: var(--panel);
            border-radius: var(--radius);
            padding: 18px 20px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .card h3 {
            margin: 0 0 6px;
            font-size: 0.95rem;
            color: var(--muted);
            font-weight: 600;
        }

        .card .metric {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-bottom: 18px;
        }

        .toolbar input,
        .toolbar select,
        .form-card input,
        .form-card select,
        .form-card textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--panel);
            font-size: 0.95rem;
        }

        .toolbar input,
        .toolbar select {
            min-width: 220px;
        }

        .button {
            padding: 10px 16px;
            border-radius: 99px;
            border: 1px solid transparent;
            background: var(--accent);
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .button.secondary {
            background: #1f2328;
            border-color: var(--border);
            /* color: var(--ink); */
            color: white;
        }

        .table-card {
            background: var(--panel);
            border-radius: var(--radius);
            padding: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }

        th {
            color: var(--muted);
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(15, 118, 110, 0.12);
            color: var(--accent);
        }

        .flash {
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .flash.success {
            background: rgba(15, 118, 110, 0.12);
            color: var(--accent);
        }

        .flash.error {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        .toast-stack {
            position: fixed;
            right: 24px;
            top: 84px;
            z-index: 50;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: var(--panel);
            box-shadow: var(--shadow);
            font-weight: 600;
        }

        .toast-success {
            color: var(--accent);
            border-color: rgba(15, 118, 110, 0.25);
            /* background: rgba(15, 118, 110, 0.08); */
            background: rgba(15, 118, 110, 0.25);
        }

        .toast-error {
            color: #b91c1c;
            border-color: rgba(239, 68, 68, 0.35);
            background: rgba(239, 68, 68, 0.08);
        }

        .toast-close {
            border: none;
            background: transparent;
            font-size: 1.2rem;
            line-height: 1;
            cursor: pointer;
            color: inherit;
        }

        .form-card {
            background: var(--panel);
            padding: 20px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            display: grid;
            gap: 14px;
            max-width: 720px;
        }

        .form-row {
            display: grid;
            gap: 6px;
        }

        .form-row label {
            font-weight: 600;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .chart-card {
            display: grid;
            gap: 12px;
        }

        .chart-wrap {
            position: relative;
            width: 100%;
            min-height: 240px;
        }

        @media (max-width: 960px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }

            .admin-sidebar {
                position: sticky;
                top: 0;
                z-index: 10;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
        }

        @media (max-width: 720px) {
            table thead {
                display: none;
            }

            table,
            table tbody,
            table tr,
            table td {
                display: block;
                width: 100%;
            }

            table tr {
                border-bottom: 1px solid var(--border);
                padding: 12px 0;
            }

            table td {
                padding: 8px 0;
                display: flex;
                justify-content: space-between;
                gap: 12px;
            }

            table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--muted);
            }

            .table-actions {
                justify-content: flex-end;
            }
        }
    </style>
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="brand">Zeno Cars</div>
            <nav class="nav-group">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users</a>
                <a class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">Suppliers</a>
                <a class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}" href="{{ route('admin.vehicles.index') }}">Vehicles</a>
                <a class="nav-link {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">Drivers</a>
                <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">Bookings</a>
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">Reports</a>
            </nav>
        </aside>
        <main>
            <div class="bg-white backdrop-blur-sm rounded-lg p-4 mb-2 border border-gray-200 shadow-sm flex justify-end">
                <div class="admin-topbar-right">
                    <details class="admin-topbar-menu">
                        <summary class="admin-topbar-trigger">
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="admin-topbar-caret" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill="currentColor" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </summary>
                        <div class="admin-topbar-menu-items">
                            <a href="{{ route('profile') }}" wire:navigate>Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Log Out</button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
            {{-- @if (request()->routeIs('admin.users.*'))
                <div class="subnav">
                    <a class="subnav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">All Users</a>
                    <a class="subnav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}" href="{{ route('admin.users.create') }}">Create User</a>
                    @if (request()->routeIs('admin.users.edit'))
                        <span class="subnav-pill">Edit User</span>
                    @endif
                </div>
            @elseif (request()->routeIs('admin.suppliers.*'))
                <div class="subnav">
                    <a class="subnav-link {{ request()->routeIs('admin.suppliers.index') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">All Suppliers</a>
                    <a class="subnav-link {{ request()->routeIs('admin.suppliers.create') ? 'active' : '' }}" href="{{ route('admin.suppliers.create') }}">Create Supplier</a>
                    @if (request()->routeIs('admin.suppliers.edit'))
                        <span class="subnav-pill">Edit Supplier</span>
                    @endif
                </div>
            @elseif (request()->routeIs('admin.vehicles.*'))
                <div class="subnav">
                    <a class="subnav-link {{ request()->routeIs('admin.vehicles.index') ? 'active' : '' }}" href="{{ route('admin.vehicles.index') }}">All Vehicles</a>
                    <a class="subnav-link {{ request()->routeIs('admin.vehicles.create') ? 'active' : '' }}" href="{{ route('admin.vehicles.create') }}">Create Vehicle</a>
                    @if (request()->routeIs('admin.vehicles.edit'))
                        <span class="subnav-pill">Edit Vehicle</span>
                    @endif
                </div>
            @elseif (request()->routeIs('admin.drivers.*'))
                <div class="subnav">
                    <a class="subnav-link {{ request()->routeIs('admin.drivers.index') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">All Drivers</a>
                    <a class="subnav-link {{ request()->routeIs('admin.drivers.create') ? 'active' : '' }}" href="{{ route('admin.drivers.create') }}">Create Driver</a>
                    @if (request()->routeIs('admin.drivers.edit'))
                        <span class="subnav-pill">Edit Driver</span>
                    @endif
                </div>
            @elseif (request()->routeIs('admin.bookings.*'))
                <div class="subnav">
                    <a class="subnav-link {{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">All Bookings</a>
                    <a class="subnav-link {{ request()->routeIs('admin.bookings.create') ? 'active' : '' }}" href="{{ route('admin.bookings.create') }}">Create Booking</a>
                    @if (request()->routeIs('admin.bookings.edit'))
                        <span class="subnav-pill">Edit Booking</span>
                    @endif
                </div>
            @endif --}}
            <div class="admin-main">
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        window.ZenoCharts = window.ZenoCharts || {};
        window.initZenoCharts = window.initZenoCharts || function () {};

        window.addEventListener('zeno-charts-refresh', function () {
            if (typeof window.initZenoCharts === 'function') {
                window.initZenoCharts();
            }
        });

        function setupToasts() {
            document.querySelectorAll('[data-toast]').forEach(function (toast) {
                const close = toast.querySelector('[data-toast-close]');
                if (close) {
                    close.addEventListener('click', function () {
                        toast.remove();
                    });
                }
                setTimeout(function () {
                    if (toast && toast.parentNode) {
                        toast.remove();
                    }
                }, 3500);
            });
        }

        document.addEventListener('DOMContentLoaded', setupToasts);

        document.addEventListener('livewire:navigated', function () {
            setupToasts();
            if (typeof window.initZenoCharts === 'function') {
                window.initZenoCharts();
            }
        });
    </script>
    @livewireScripts
</body>
</html>
