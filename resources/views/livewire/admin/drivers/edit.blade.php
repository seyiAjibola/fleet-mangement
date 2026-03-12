<section>
    <x-admin.header pageTitle="Edit Driver" pageSubTitle="Update driver details and assignments." />

    <form class="form-card" wire:submit.prevent="save">
        <div class="form-row">
            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" wire:model.defer="supplier_id">
                <option value="">Select supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->business_name }}</option>
                @endforeach
            </select>
            @error('supplier_id') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="vehicle_id">Vehicle</label>
            <select id="vehicle_id" wire:model.defer="vehicle_id">
                <option value="">Select vehicle</option>
                @foreach ($vehicles as $vehicle)
                    <option value="{{ $vehicle->vehicle_id }}">{{ $vehicle->plate_number }}</option>
                @endforeach
            </select>
            @error('vehicle_id') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="driver_name">Driver Name</label>
            <input id="driver_name" type="text" wire:model.defer="driver_name" />
            @error('driver_name') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="phone_number">Phone Number</label>
            <input id="phone_number" type="text" wire:model.defer="phone_number" />
            @error('phone_number') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="license_number">License Number</label>
            <input id="license_number" type="text" wire:model.defer="license_number" />
            @error('license_number') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="years_experience">Years Experience</label>
            <input id="years_experience" type="number" wire:model.defer="years_experience" />
            @error('years_experience') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="languages">Languages</label>
            <input id="languages" type="text" wire:model.defer="languages" />
            @error('languages') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="professional_experience">Professional Experience</label>
            <textarea id="professional_experience" wire:model.defer="professional_experience"></textarea>
            @error('professional_experience') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="status">Status</label>
            <input id="status" type="text" wire:model.defer="status" />
            @error('status') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Update</button>
            <a class="button secondary" href="{{ route('admin.drivers.index') }}">Cancel</a>
        </div>
    </form>
</section>
