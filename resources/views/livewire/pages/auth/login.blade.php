<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function mount(): void
    {
        if (auth()->check()) {
            $this->redirectRoute(auth()->user()->homeRouteName(), navigate: true);
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route(auth()->user()->homeRouteName(), absolute: false), navigate: true);
    }
}; ?>

<div class="rounded-[28px] border border-[var(--border)] bg-white/70 p-7 shadow-[0_18px_48px_rgba(31,35,40,0.08)] backdrop-blur-sm sm:p-9">
    <div class="mb-8">
        <span class="mb-3 inline-flex rounded-full border border-[rgba(15,118,110,0.2)] bg-[rgba(15,118,110,0.08)] px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-[var(--accent)]">
            Admin Access
        </span>
        <h2 class="text-3xl font-bold tracking-[-0.03em] text-[var(--ink)]">Welcome back</h2>
        <p class="mt-2 text-sm leading-6 text-[rgba(31,35,40,0.7)]">
            Use your admin credentials to continue into the Zenocar control panel.
        </p>
    </div>

    <x-auth-session-status
        class="auth-status"
        :status="session('status')"
    />

    <form wire:submit="login" class="form-card">
        <div class="form-row">
            <label for="email">{{ __('Email') }}</label>
            <input
                wire:model="form.email"
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="username"
                placeholder="admin@zenocar.com"
            />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="form-row">
            <div class="mb-2 flex items-center justify-between gap-4">
                <label for="password" class="mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a
                        class="auth-inline-link focus:ring-2 focus:ring-[var(--accent)] focus:ring-offset-2 focus:ring-offset-transparent"
                        href="{{ route('password.request') }}"
                        wire:navigate
                    >
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <input
                wire:model="form.password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
            />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-sm text-red-600" />
        </div>

        <label for="remember" class="auth-check">
            <input
                wire:model="form.remember"
                id="remember"
                type="checkbox"
                name="remember"
            >
            <span>{{ __('Keep me signed in on this device') }}</span>
        </label>

        <div class="form-actions">
            <button
                type="submit"
                class="button w-full px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent)] focus:ring-offset-2 focus:ring-offset-transparent"
            >
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</div>
