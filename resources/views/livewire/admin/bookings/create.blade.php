<section>
    <x-admin.header pageTitle="Create Booking" pageSubTitle="Schedule a new customer booking." />

    <form class="form-card" wire:submit.prevent="save">
        <div class="form-row">
            <label for="customer_name">Customer Name</label>
            <input id="customer_name" type="text" wire:model.defer="customer_name" />
            @error('customer_name') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="customer_phone">Customer Phone</label>
            <input id="customer_phone" type="text" wire:model.defer="customer_phone" />
            @error('customer_phone') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="pickup_location">Pickup Location</label>
            <input id="pickup_location" type="text" wire:model.defer="pickup_location" />
            @error('pickup_location') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="dropoff_location">Dropoff Location</label>
            <input id="dropoff_location" type="text" wire:model.defer="dropoff_location" />
            @error('dropoff_location') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="pickup_time">Pickup Time</label>
            <input id="pickup_time" type="datetime-local" wire:model.defer="pickup_time" />
            @error('pickup_time') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="dropoff_time">Dropoff Time</label>
            <input id="dropoff_time" type="datetime-local" wire:model.defer="dropoff_time" />
            <small>Auto-fills to pickup time + 2 hours. Edit if needed.</small>
            @error('dropoff_time') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="vehicle_category">Vehicle Category</label>
            <input id="vehicle_category" type="text" wire:model.defer="vehicle_category" />
            @error('vehicle_category') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="booking_source">Booking Source</label>
            <input id="booking_source" type="text" wire:model.defer="booking_source" />
            @error('booking_source') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="assigned_vehicle">Assigned Vehicle</label>
            <select id="assigned_vehicle" wire:model.defer="assigned_vehicle">
                <option value="">Unassigned</option>
                @foreach ($vehicles as $vehicle)
                    <option value="{{ $vehicle->vehicle_id }}">{{ $vehicle->plate_number }}</option>
                @endforeach
            </select>
            @error('assigned_vehicle') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="assigned_driver">Assigned Driver</label>
            <select id="assigned_driver" wire:model.defer="assigned_driver">
                <option value="">Unassigned</option>
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->driver_id }}">{{ $driver->driver_name }}</option>
                @endforeach
            </select>
            @error('assigned_driver') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <label for="status">Status</label>
            <select id="status" wire:model.defer="status">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="in_transit">In Transit</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="rejected">Rejected</option>
            </select>
            @error('status') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Save</button>
            <a class="button secondary" href="{{ route('admin.bookings.index') }}">Cancel</a>
        </div>
    </form>
</section>
