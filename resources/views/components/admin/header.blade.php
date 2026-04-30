@props([
    'pageTitle' => null,
    'pageSubTitle' => null,
])

<div class="mb-4">
    <div class="admin-topbar-inner">
        <div class="admin-topbar-left">
            <a class="admin-topbar-logo" href="{{ route('dashboard') }}" wire:navigate>
                <span class="admin-topbar-logo-mark">Zeno</span>
            </a>
            <nav class="admin-topbar-nav">
                <a class="admin-topbar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" wire:navigate>
                    Dashboard
                </a>
            </nav>
            @if ($pageTitle || $pageSubTitle)
                <div class="admin-topbar-meta">
                    @if ($pageTitle)
                        <h1 class="page-title">{{ $pageTitle }}</h1>
                    @endif
                    @if ($pageSubTitle)
                        <p class="page-subtitle">{{ $pageSubTitle }}</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- <div class="admin-topbar-right">
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
        </div> --}}
    </div>
</div>
