<section>
    <x-admin.header pageTitle="Create Vehicle" pageSubTitle="Register a new vehicle in the fleet." />

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
            <label for="vehicle_make">Make</label>
            <input id="vehicle_make" type="text" wire:model.defer="vehicle_make" />
            @error('vehicle_make') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="vehicle_model">Model</label>
            <input id="vehicle_model" type="text" wire:model.defer="vehicle_model" />
            @error('vehicle_model') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="vehicle_year">Year</label>
            <input id="vehicle_year" type="number" wire:model.defer="vehicle_year" />
            @error('vehicle_year') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="vehicle_color">Color</label>
            <input id="vehicle_color" type="text" wire:model.defer="vehicle_color" />
            @error('vehicle_color') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="plate_number">Plate Number</label>
            <input id="plate_number" type="text" wire:model.defer="plate_number" />
            @error('plate_number') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="vehicle_category">Category</label>
            <input id="vehicle_category" type="text" wire:model.defer="vehicle_category" />
            @error('vehicle_category') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="passenger_capacity">Passenger Capacity</label>
            <input id="passenger_capacity" type="number" wire:model.defer="passenger_capacity" />
            @error('passenger_capacity') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="vehicle_condition">Condition</label>
            <input id="vehicle_condition" type="text" wire:model.defer="vehicle_condition" />
            @error('vehicle_condition') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="air_condition">Air Condition</label>
            <select id="air_condition" wire:model.defer="air_condition">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            @error('air_condition') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="vehicle_location">Location</label>
            <input id="vehicle_location" type="text" wire:model.defer="vehicle_location" />
            @error('vehicle_location') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="status">Status</label>
            <input id="status" type="text" wire:model.defer="status" />
            @error('status') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Save</button>
            <a class="button secondary" href="{{ route('admin.vehicles.index') }}">Cancel</a>
        </div>
    </form>
</section>
