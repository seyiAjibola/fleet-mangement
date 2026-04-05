@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
    <section style="display: grid; gap: 18px;">
        <x-admin.header pageTitle="Profile" pageSubTitle="Review your account details and manage your sign-in settings." />

        <div class="card" style="display: grid; gap: 18px;">
            <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start; flex-wrap: wrap;">
                <div>
                    <h3 style="margin: 0;">{{ auth()->user()->name }}</h3>
                    <p style="margin: 6px 0 0; color: var(--muted);">{{ auth()->user()->email }}</p>
                </div>
                <div class="table-actions">
                    <a class="button" href="{{ route(auth()->user()->homeRouteName()) }}">
                        Open Workspace
                    </a>
                    @if (auth()->user()->isAdmin())
                        <a class="button secondary" href="{{ route('admin.users.index') }}">
                            Manage Users
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-grid">
                <div class="card">
                    <h3>Role</h3>
                    <div>{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <div class="card">
                    <h3>Email</h3>
                    <div>{{ auth()->user()->email }}</div>
                </div>
                <div class="card">
                    <h3>NIN</h3>
                    <div>{{ auth()->user()->nin ?: 'Not added' }}</div>
                </div>
                <div class="card">
                    <h3>Email Verification</h3>
                    <div>{{ auth()->user()->email_verified_at ? 'Verified' : 'Not Verified' }}</div>
                </div>
            </div>
        </div>

        <div style="display: grid; gap: 18px; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
            <livewire:profile.update-profile-information-form />
            <livewire:profile.update-password-form />
        </div>

        <div class="card" style="border-color: rgba(220, 38, 38, 0.2);">
            <livewire:profile.delete-user-form />
        </div>
    </section>
@endsection
