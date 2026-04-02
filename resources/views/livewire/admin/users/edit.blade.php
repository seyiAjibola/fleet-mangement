<section>
    <x-admin.header pageTitle="Edit User" pageSubTitle="Update account details or reset password." />

    <form class="form-card" wire:submit.prevent="save">
        <div class="form-row">
            <label for="name">Name</label>
            <input id="name" type="text" wire:model.defer="name" />
            @error('name') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="nin">NIN</label>
            <input id="nin" type="text" wire:model.defer="nin" />
            @error('nin') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="email">Email</label>
            <input id="email" type="email" wire:model.defer="email" />
            @error('email') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="password">New Password</label>
            <input id="password" type="password" wire:model.defer="password" />
            @error('password') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" wire:model.defer="password_confirmation" />
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Update</button>
            <a class="button secondary" href="{{ route('admin.users.index') }}">Cancel</a>
        </div>
    </form>
</section>
