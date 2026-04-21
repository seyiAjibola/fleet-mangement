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
            --panel-soft: #f6f1ea;
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
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            max-width: 100%;
        }

        .admin-shell {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .admin-shell > main {
            min-width: 0;
        }

        .admin-sidebar-backdrop {
            display: none;
        }

        .admin-sidebar {
            padding: 32px 24px;
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, 0.12), transparent 32%),
                linear-gradient(180deg, #fffdfa 0%, #f5efe8 100%);
            border-right: 1px solid var(--border);
            position: relative;
        }

        .brand {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            margin-bottom: 28px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(231, 226, 219, 0.9);
            box-shadow: 0 12px 32px rgba(31, 35, 40, 0.06);
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
            background: rgba(255, 255, 255, 0.58);
            color: var(--muted);
            font-weight: 500;
            border: 1px solid transparent;
            backdrop-filter: blur(10px);
        }

        .nav-link-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .nav-link-icon {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            opacity: 0.82;
        }

        .nav-link.active .nav-link-icon {
            opacity: 1;
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.16), rgba(15, 118, 110, 0.08));
            color: var(--accent);
            border-color: rgba(15, 118, 110, 0.24);
            box-shadow: 0 10px 24px rgba(15, 118, 110, 0.12);
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
            background: rgba(255, 255, 255, 0.88);
            color: var(--ink);
            border-color: rgba(31, 35, 40, 0.08);
        }

        .admin-main {
            padding: 16px 40px 48px;
            min-width: 0;
        }

        .admin-mobile-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            padding: 0;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--ink);
            cursor: pointer;
        }

        .admin-mobile-toggle svg {
            width: 20px;
            height: 20px;
        }

        .page-title {
            font-size: 1.8rem;
            margin: 0 0 6px;
            font-weight: 700;
        }

        .page-subtitle {
            color: rgba(31, 35, 40, 0.72);
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
            color: var(--ink);
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
            border-radius: 14px;
            border: 1px solid rgba(31, 35, 40, 0.08);
            background: linear-gradient(180deg, #fffdfa 0%, #f8f3ec 100%);
            font-size: 0.95rem;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
            transition: border-color 160ms ease, box-shadow 160ms ease, background 160ms ease;
        }

        .toolbar input,
        .toolbar select {
            min-width: 220px;
        }

        .toolbar label {
            display: inline-block;
            margin-bottom: 6px;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: #374151;
        }

        .toolbar input:focus,
        .toolbar select:focus,
        .form-card input:focus,
        .form-card select:focus,
        .form-card textarea:focus {
            outline: none;
            border-color: rgba(15, 118, 110, 0.35);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.08);
            background: #fffefb;
        }

        .button {
            padding: 5px 16px;
            border-radius: 99px;
            border: 1px solid transparent;
            background: var(--accent);
            color: white;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .button.secondary {
            background: #1f2328;
            border-color: var(--border);
            /* color: var(--ink); */
            color: white;
        }

        .icon-button {
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 12px;
            flex: 0 0 auto;
        }

        .icon-button svg {
            width: 18px;
            height: 18px;
        }

        .icon-button.icon-view {
            background: rgba(15, 118, 110, 0.12);
            color: #0f766e;
            border-color: rgba(15, 118, 110, 0.2);
        }

        .icon-button.icon-edit {
            background: rgba(31, 35, 40, 0.12);
            color: #1f2328;
            border-color: rgba(31, 35, 40, 0.16);
        }

        .icon-button.icon-delete,
        .icon-button.icon-reject,
        .icon-button.icon-cancel,
        .icon-button.icon-close {
            background: rgba(220, 38, 38, 0.12);
            color: #dc2626;
            border-color: rgba(220, 38, 38, 0.2);
        }

        .icon-button.icon-confirm,
        .icon-button.icon-upload,
        .icon-button.icon-star {
            background: rgba(15, 118, 110, 0.12);
            color: #0f766e;
            border-color: rgba(15, 118, 110, 0.2);
        }

        .icon-button.icon-back {
            background: rgba(31, 35, 40, 0.12);
            color: #1f2328;
            border-color: rgba(31, 35, 40, 0.16);
        }

        .table-card {
            background: var(--panel);
            border-radius: var(--radius);
            padding: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow-x: auto;
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

        thead th {
            background: linear-gradient(180deg, #f7f4ef 0%, #f1ece5 100%);
        }

        th {
            color: var(--ink);
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            font-size: 0.78rem;
            box-shadow: inset 0 -1px 0 rgba(31, 35, 40, 0.06);
        }

        .table-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .vehicle-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .vehicle-gallery-item {
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            background: var(--panel-strong);
        }

        .vehicle-gallery-media {
            aspect-ratio: 4 / 3;
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.08), rgba(245, 158, 11, 0.12));
        }

        .vehicle-gallery-trigger {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0;
            border: none;
            background: transparent;
            cursor: zoom-in;
        }

        .vehicle-gallery-media img,
        .vehicle-upload-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .vehicle-gallery-meta {
            padding: 14px;
            display: grid;
            gap: 12px;
        }

        .vehicle-empty-state {
            border: 1px dashed rgba(15, 118, 110, 0.35);
            border-radius: 16px;
            padding: 18px;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
            background: rgba(15, 118, 110, 0.05);
        }

        .vehicle-upload-preview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            margin-top: 14px;
        }

        .vehicle-upload-preview-item {
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: var(--panel-strong);
        }

        .admin-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(31, 35, 40, 0.55);
            backdrop-filter: blur(4px);
            z-index: 60;
        }

        .admin-modal-shell {
            position: fixed;
            inset: 0;
            padding: 24px;
            display: grid;
            place-items: center;
            z-index: 61;
        }

        .admin-modal-card {
            width: min(760px, 100%);
            max-height: calc(100vh - 48px);
            overflow: auto;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 22px;
            box-shadow: var(--shadow);
        }

        .admin-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 16px;
            margin-bottom: 18px;
        }

        .vehicle-preview-modal {
            width: min(960px, 100%);
        }

        .vehicle-preview-media {
            border-radius: 18px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.08), rgba(245, 158, 11, 0.12));
        }

        .vehicle-preview-media img {
            width: 100%;
            max-height: 75vh;
            object-fit: contain;
            display: block;
            background: #0f172a;
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

        .badge[data-status='active'],
        .badge[data-status='available'],
        .badge[data-status='confirmed'],
        .badge[data-status='completed'] {
            background: rgba(15, 118, 110, 0.12);
            color: #0f766e;
        }

        .badge[data-status='pending'],
        .badge[data-status='in_transit'] {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .badge[data-status='inactive'],
        .badge[data-status='unavailable'] {
            background: rgba(31, 35, 40, 0.12);
            color: #1f2328;
        }

        .badge[data-status='cancelled'],
        .badge[data-status='rejected'] {
            background: rgba(220, 38, 38, 0.12);
            color: #dc2626;
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

        .form-card header h2 {
            color: var(--ink);
        }

        .form-card header p {
            color: rgba(31, 35, 40, 0.72);
        }

        .form-row {
            display: grid;
            gap: 6px;
        }

        .form-row label {
            font-weight: 600;
            color: var(--ink);
            font-size: 0.85rem;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
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
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: min(300px, 82vw);
                max-width: 100%;
                overflow-y: auto;
                z-index: 80;
                border-right: 1px solid var(--border);
                border-bottom: none;
                box-shadow: var(--shadow);
                transform: translateX(-100%);
                transition: transform 180ms ease;
            }

            body.admin-sidebar-open {
                overflow: hidden;
            }

            body.admin-sidebar-open .admin-sidebar {
                transform: translateX(0);
            }

            .admin-sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(31, 35, 40, 0.45);
                z-index: 70;
            }

            body.admin-sidebar-open .admin-sidebar-backdrop {
                display: block;
            }

            .admin-mobile-toggle {
                display: inline-flex;
            }

            .admin-topbar-mobile {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .admin-main {
                padding: 16px 18px 40px;
            }

            .card-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }

            .toolbar {
                justify-content: space-between;
                align-items: stretch;
            }

            .toolbar > * {
                max-width: 100%;
            }

            .toolbar > div[style*='display: flex'] {
                width: 100%;
                flex-wrap: wrap !important;
                align-items: stretch !important;
            }

            .toolbar input,
            .toolbar select {
                min-width: 0;
            }

            .table-actions {
                width: 100%;
            }

            .table-actions .button,
            .table-actions a.button,
            .form-actions .button,
            .form-actions a.button {
                flex: 1 1 calc(50% - 12px);
            }
        }

        @media (max-width: 720px) {
            .admin-topbar-trigger span {
                max-width: 120px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .toolbar,
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar .button,
            .toolbar a.button,
            .form-actions .button,
            .form-actions a.button {
                width: 100%;
            }

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

            .table-actions .button,
            .table-actions a.button {
                flex: 1 1 100%;
                width: 100%;
            }

            .admin-modal-shell {
                padding: 12px;
            }
        }
    </style>
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="admin-shell">
        <button class="admin-sidebar-backdrop" type="button" aria-label="Close navigation"></button>
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="brand">Zenocar</div>
            <nav class="nav-group">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <span class="nav-link-label"><span class="nav-link-icon"><x-admin.icon name="dashboard" /></span><span>Dashboard</span></span>
                </a>
                @if (auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <span class="nav-link-label"><span class="nav-link-icon"><x-admin.icon name="users" /></span><span>Users</span></span>
                    </a>
                @endif
                <a class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">
                    <span class="nav-link-label"><span class="nav-link-icon"><x-admin.icon name="suppliers" /></span><span>Suppliers</span></span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}" href="{{ route('admin.vehicles.index') }}">
                    <span class="nav-link-label"><span class="nav-link-icon"><x-admin.icon name="vehicles" /></span><span>Vehicles</span></span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">
                    <span class="nav-link-label"><span class="nav-link-icon"><x-admin.icon name="drivers" /></span><span>Drivers</span></span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.compliance.*') ? 'active' : '' }}" href="{{ route('admin.compliance.index') }}">
                    <span class="nav-link-label"><span class="nav-link-icon"><x-admin.icon name="compliance" /></span><span>Compliance</span></span>
                </a>
                {{-- <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                    <span class="nav-link-label"><span class="nav-link-icon"><x-admin.icon name="bookings" /></span><span>Bookings</span></span>
                </a> --}}
                @if (auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <span class="nav-link-label"><span class="nav-link-icon"><x-admin.icon name="reports" /></span><span>Reports</span></span>
                    </a>
                @endif
            </nav>
        </aside>
        <main>
            <div class="bg-white/85 backdrop-blur-sm rounded-2xl p-4 mb-2 border border-gray-200 shadow-sm flex justify-between items-center gap-3" style="background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(249,246,242,0.92)); box-shadow: 0 18px 45px rgba(31, 35, 40, 0.08);">
                <div class="admin-topbar-mobile">
                    <button class="admin-mobile-toggle" type="button" aria-label="Open navigation" aria-controls="admin-sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill="currentColor" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4A1 1 0 013 5zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                    <div style="width: 12px; height: 12px; border-radius: 999px; background: linear-gradient(135deg, var(--accent), #14b8a6); box-shadow: 0 0 0 6px rgba(15, 118, 110, 0.08);"></div>
                    <div style="min-width: 0;">
                        <div style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); font-weight: 700;">Zenocar Admin</div>
                        <div style="font-size: 0.95rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->isAdmin() ? 'Operations Control' : 'Staff Workspace' }}</div>
                    </div>
                </div>
                <div class="admin-topbar-right">
                    <livewire:admin.notifications.center />
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
            @elseif (request()->routeIs('admin.compliance.*'))
                <div class="subnav">
                    <a class="subnav-link {{ request()->routeIs('admin.compliance.index') ? 'active' : '' }}" href="{{ route('admin.compliance.index') }}">Compliance Dashboard</a>
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
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
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

        function initializeToast(toast) {
            if (toast.dataset.toastInitialized) {
                return;
            }

            toast.dataset.toastInitialized = 'true';

            setTimeout(function () {
                if (toast && toast.parentNode) {
                    toast.remove();
                }
            }, 6000);
        }

        function setupToasts() {
            document.querySelectorAll('[data-toast]').forEach(initializeToast);
        }

        function bindToastClose() {
            if (document.toastCloseBound) {
                return;
            }

            document.toastCloseBound = true;
            document.addEventListener('click', function (event) {
                const trigger = event.target.closest('[data-toast-close]');
                if (!trigger) {
                    return;
                }
                const toast = trigger.closest('[data-toast]');
                if (toast) {
                    toast.remove();
                }
            });
        }

        function setupAdminSidebar() {
            const body = document.body;
            const toggle = document.querySelector('.admin-mobile-toggle');
            const backdrop = document.querySelector('.admin-sidebar-backdrop');
            const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');

            function closeSidebar() {
                body.classList.remove('admin-sidebar-open');
            }

            function toggleSidebar() {
                body.classList.toggle('admin-sidebar-open');
            }

            if (toggle) {
                toggle.onclick = toggleSidebar;
            }

            if (backdrop) {
                backdrop.onclick = closeSidebar;
            }

            navLinks.forEach(function (link) {
                link.addEventListener('click', closeSidebar);
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 960) {
                    closeSidebar();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', setupToasts);
        document.addEventListener('DOMContentLoaded', setupAdminSidebar);

        setupToasts();
        bindToastClose();
        document.addEventListener('livewire:navigated', function () {
            setupToasts();
            setupAdminSidebar();
            document.body.classList.remove('admin-sidebar-open');
            if (typeof window.initZenoCharts === 'function') {
                window.initZenoCharts();
            }
        });

        if (window.Livewire) {
            window.Livewire.hook('message.processed', function () {
                setupToasts();
            });
        }
    </script>
    @livewireScripts
</body>
</html>
