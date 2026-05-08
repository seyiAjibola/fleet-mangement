<section>
    <x-admin.header pageTitle="Vehicles" pageSubTitle="Fleet inventory, capacity, and availability.">
        <a class="button" href="{{ route('admin.vehicles.create') }}">Add New Vehicle</a>
    </x-admin.header>
    <x-admin.toast />

    <div class="toolbar" style="justify-content: space-between">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: end; flex-wrap: wrap">
            <div>
                <label for="vehicle-type">Vehicle Type</label>
                <select id="vehicle-type" wire:model.defer="vehicleType">
                    <option value="">All</option>
                    <option value="SUV">SUV</option>
                    <option value="SEDAN">SEDAN</option>
                    <option value="TRUCK">TRUCK</option>
                    <option value="VAN">VAN</option>
                </select>
            </div>
            <div>
                <label for="vehicle-make">Vehicle Make</label>
                <input id="vehicle-make" type="search" wire:model.defer="vehicleMake" placeholder="Toyota" />
            </div>
            <div>
                <label for="vehicle-model">Vehicle Model</label>
                <input id="vehicle-model" type="search" wire:model.defer="vehicleModel" placeholder="LX600" />
            </div>
            <div>
                <label for="vehicle-condition">Vehicle Condition</label>
                <select id="vehicle-condition" wire:model.defer="vehicleCondition">
                    <option value="">All</option>
                    <option value="standard">Standard</option>
                    <option value="average">Average</option>
                    <option value="excellent">Excellent</option>
                </select>
            </div>
            <div>
                <label for="vehicle-plate-number">Vehicle Plate No</label>
                <input id="vehicle-plate-number" type="search" wire:model.defer="plateNumber" placeholder="Plate no" />
            </div>
            <div>
                <label for="vehicle-year-filter">Year</label>
                <input id="vehicle-year-filter" type="number" min="2010" max="2027" wire:model.defer="year" placeholder="2010 - 2027" />
            </div>
            <div>
                <label for="vehicle-fuel-type">Fuel Type</label>
                <select id="vehicle-fuel-type" wire:model.defer="fuelType">
                    <option value="">All</option>
                    <option value="gas">Gas</option>
                    <option value="diesel">Diesel</option>
                </select>
            </div>
            <div>
                <label for="vehicle-color-filter">Vehicle Color</label>
                <input id="vehicle-color-filter" type="search" wire:model.defer="vehicleColor" placeholder="Color" />
            </div>
            <div>
                <label for="vehicle-status">Status</label>
                <select id="vehicle-status" wire:model.defer="status">
                    <option value="">All</option>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
            </div>
            <div class="d-flex justify-content-start">
                <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
                <button class="button secondary" type="button" wire:click="resetFilters">Reset</button>
            </div>
        </div>
    </div>
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Condition</th>
                    <th>Year</th>
                    <th>Plate</th>
                    <th>Fuel</th>
                    <th>Status</th>
                    <th>Supplier</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr>
                        <td data-label="Type">{{ $vehicle->vehicle_category }}</td>
                        <td data-label="Make">{{ $vehicle->vehicle_make }}</td>
                        <td data-label="Model">{{ $vehicle->vehicle_model }}</td>
                        <td data-label="Condition">{{ ucfirst($vehicle->vehicle_condition) }}</td>
                        <td data-label="Year">{{ $vehicle->vehicle_year }}</td>
                        <td data-label="Plate">{{ $vehicle->plate_number }}</td>
                        <td data-label="Fuel">{{ ucfirst($vehicle->fuel_type ?? '—') }}</td>
                        <td data-label="Status"><span class="badge" data-status="{{ $vehicle->status }}">{{ $vehicle->status }}</span></td>
                        <td data-label="Supplier">{{ $vehicle->supplier->business_name }}</span></td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary icon-button icon-view" href="{{ route('admin.vehicles.show', $vehicle) }}" aria-label="View vehicle" title="View vehicle"><x-admin.icon name="view" /></a>
                                <a class="button secondary icon-button icon-edit" href="{{ route('admin.vehicles.edit', $vehicle) }}" aria-label="Edit vehicle" title="Edit vehicle"><x-admin.icon name="edit" /></a>
                                @if ($vehicle->status === 'available')
                                    <button class="button secondary icon-button icon-cancel" type="button" wire:click="makeUnavailable({{ $vehicle->vehicle_id }})" 
                                        onclick="if(!confirm('Mark this vehicle as unavailable?')){event.stopImmediatePropagation();event.preventDefault();}"
                                        aria-label="Make vehicle unavailable" title="Make vehicle unavailable"><x-admin.icon name="cancel" /></button>
                                @else
                                    <button class="button secondary icon-button icon-confirm" type="button" wire:click="makeAvailable({{ $vehicle->vehicle_id }})"
                                        onclick="if(!confirm('Mark this vehicle as available?')){event.stopImmediatePropagation();event.preventDefault();}"
                                        aria-label="Make vehicle available" title="Make vehicle available"><x-admin.icon name="confirm" /></button>
                                @endif
                                <button class="button secondary icon-button icon-delete" type="button" wire:click="delete({{ $vehicle->vehicle_id }})" 
                                    onclick="if(!confirm('Delete this vehicle?')){event.stopImmediatePropagation();event.preventDefault();}"
                                    aria-label="Delete vehicle" title="Delete vehicle"><x-admin.icon name="delete" /></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No vehicles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $vehicles->links() }}
    </div>
</section>
