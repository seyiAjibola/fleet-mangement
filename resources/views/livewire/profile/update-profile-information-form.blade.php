<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="form-card">
        <div class="form-row">
            <label for="name">Name</label>
            <input id="name" type="text" wire:model.defer="name" required autofocus autocomplete="name" />
            @error('name') <p class="text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="email">Email</label>
            <input id="email" type="email" wire:model.defer="email" required autocomplete="username" />
            @error('email') <p class="text-sm text-rose-600">{{ $message }}</p> @enderror

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <p class="text-sm text-amber-700">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" wire:click.prevent="sendVerification" class="button secondary" style="margin-left: 0.5rem;">
                        {{ __('Re-send verification email') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="text-sm font-semibold text-emerald-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Save</button>
            <x-action-message class="ms-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
