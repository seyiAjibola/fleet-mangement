<section>
    <x-admin.header pageTitle="Create Supplier" pageSubTitle="Add a new supplier profile." />

    <form class="form-card" wire:submit.prevent="save">
        <div class="form-row">
            <label for="business_name">Company Name</label>
            <input id="business_name" type="text" wire:model.defer="business_name" />
            @error('business_name') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="business_type">Business Type</label>
            <input id="business_type" type="text" wire:model.defer="business_type" />
            @error('business_type') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="contact_person">Contact Person</label>
            <input id="contact_person" type="text" wire:model.defer="contact_person" />
            @error('contact_person') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="phone_number">Contact Number</label>
            <input id="phone_number" type="text" wire:model.defer="phone_number" />
            @error('phone_number') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="cac_no">CAC No</label>
            <input id="cac_no" type="text" wire:model.defer="cac_no" />
            @error('cac_no') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="tin">TIN</label>
            <input id="tin" type="text" wire:model.defer="tin" />
            @error('tin') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="email">Email</label>
            <input id="email" type="email" wire:model.defer="email" />
            @error('email') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="city">City</label>
            <input id="city" type="text" wire:model.defer="city" />
            @error('city') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="business_address">Location / Address</label>
            <textarea id="business_address" wire:model.defer="business_address"></textarea>
            @error('business_address') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="years_in_business">Years in Business</label>
            <input id="years_in_business" type="number" wire:model.defer="years_in_business" />
            @error('years_in_business') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="instagram_page">Instagram Page</label>
            <input id="instagram_page" type="text" wire:model.defer="instagram_page" />
            @error('instagram_page') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="website">Website</label>
            <input id="website" type="text" wire:model.defer="website" />
            @error('website') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="status">Status</label>
            <input id="status" type="text" wire:model.defer="status" />
            @error('status') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="supplier_score">Supplier Score</label>
            <input id="supplier_score" type="number" wire:model.defer="supplier_score" />
            @error('supplier_score') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="supplier_tier">Supplier Tier</label>
            <input id="supplier_tier" type="text" wire:model.defer="supplier_tier" />
            @error('supplier_tier') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Save</button>
            <a class="button secondary" href="{{ route('admin.suppliers.index') }}">Cancel</a>
        </div>
    </form>
</section>
