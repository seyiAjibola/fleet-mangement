<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="form-card">
        <div class="form-row">
            <label for="update_password_current_password">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" type="password" wire:model.defer="current_password" autocomplete="current-password" />
            @error('current_password') <p class="text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="update_password_password">{{ __('New Password') }}</label>
            <input id="update_password_password" type="password" wire:model.defer="password" autocomplete="new-password" />
            @error('password') <p class="text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="update_password_password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" type="password" wire:model.defer="password_confirmation" autocomplete="new-password" />
            @error('password_confirmation') <p class="text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-actions">
            <button class="button" type="submit">{{ __('Save') }}</button>
            <x-action-message class="ms-3" on="password-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
