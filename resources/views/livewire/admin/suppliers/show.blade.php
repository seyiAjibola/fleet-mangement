<section>
    <x-admin.header pageTitle="Supplier Details" pageSubTitle="Full supplier profile and registration information." />

    <div class="card" style="display: grid; gap: 18px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">{{ $supplier->business_name }}</h3>
                <p style="margin: 6px 0 0; color: var(--muted);">{{ $supplier->business_type }}</p>
            </div>
            <div class="table-actions">
                <a class="button secondary icon-button icon-edit" href="{{ route('admin.suppliers.edit', $supplier) }}" aria-label="Edit supplier" title="Edit supplier"><x-admin.icon name="edit" /></a>
                <a class="button secondary icon-button icon-back" href="{{ route('admin.suppliers.index') }}" aria-label="Back to suppliers" title="Back to suppliers"><x-admin.icon name="back" /></a>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Contact Person</h3>
                <div>{{ $supplier->contact_person ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Contact Number</h3>
                <div>{{ $supplier->phone_number ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Number of Cars</h3>
                <div>{{ $supplier->vehicles_count }}</div>
            </div>
            <div class="card">
                <h3>Status</h3>
                <div><span class="badge" data-status="{{ $supplier->status }}">{{ $supplier->status }}</span></div>
            </div>
        </div>

        <div class="table-card">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 220px;">Company Name</th>
                        <td>{{ $supplier->business_name }}</td>
                    </tr>
                    <tr>
                        <th>CAC No</th>
                        <td>{{ $supplier->cac_no ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>TIN</th>
                        <td>{{ $supplier->tin ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $supplier->email }}</td>
                    </tr>
                    <tr>
                        <th>Location / Address</th>
                        <td>{{ $supplier->city }}{{ $supplier->business_address ? ', ' . $supplier->business_address : '' }}</td>
                    </tr>
                    <tr>
                        <th>Years in Business</th>
                        <td>{{ $supplier->years_in_business }}</td>
                    </tr>
                    <tr>
                        <th>Supplier Tier</th>
                        <td>{{ $supplier->supplier_tier ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Supplier Score</th>
                        <td>{{ $supplier->supplier_score }}</td>
                    </tr>
                    <tr>
                        <th>Instagram Page</th>
                        <td>{{ $supplier->instagram_page ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Website</th>
                        <td>{{ $supplier->website ?: '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                <div>
                    <h3 style="margin-bottom: 4px;">Vehicles Under Supplier</h3>
                    <p style="margin: 0; color: var(--muted);">Open a vehicle record directly from the supplier profile and review any assigned drivers.</p>
                </div>
                <div>
                    <span class="badge">{{ $supplier->vehicles->count() }} vehicles</span>
                    <a class="button" href="{{ route('admin.vehicles.create') }}">Add Vehicle</a>
                </div>
                
            </div>

            <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Plate Number</th>
                            <th>Category</th>
                            <th>Drivers</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supplier->vehicles as $vehicle)
                            <tr>
                                <td data-label="Vehicle">
                                    <a href="{{ route('admin.vehicles.show', $vehicle) }}" style="color: var(--accent); font-weight: 600;">
                                        {{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }}
                                    </a>
                                </td>
                                <td data-label="Plate Number">{{ $vehicle->plate_number }}</td>
                                <td data-label="Category">{{ $vehicle->vehicle_category }}</td>
                                <td data-label="Drivers">
                                    @if ($vehicle->drivers->isEmpty())
                                        —
                                    @else
                                        {{ $vehicle->drivers->pluck('driver_name')->join(', ') }}
                                    @endif
                                </td>
                                <td data-label="Status"><span class="badge" data-status="{{ $vehicle->status }}">{{ $vehicle->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No vehicles found for this supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                <div>
                    <h3 style="margin-bottom: 4px;">Drivers Under Supplier</h3>
                    <p style="margin: 0; color: var(--muted);">Open a driver record directly from the supplier profile and review their assigned vehicle.</p>
                </div>
                <div>
                    <span class="badge">{{ $supplier->drivers->count() }} drivers</span>
                    <a class="button" href="{{ route('admin.drivers.create') }}">Add Driver</a>
                </div>
                
            </div>

            <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Phone Number</th>
                            <th>License Number</th>
                            <th>Assigned Vehicle</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supplier->drivers as $driver)
                            <tr>
                                <td data-label="Driver">
                                    <a href="{{ route('admin.drivers.show', $driver) }}" style="color: var(--accent); font-weight: 600;">
                                        {{ $driver->driver_name }}
                                    </a>
                                </td>
                                <td data-label="Phone Number">{{ $driver->phone_number }}</td>
                                <td data-label="License Number">{{ $driver->license_number }}</td>
                                <td data-label="Assigned Vehicle">
                                    @if ($driver->vehicle)
                                        <a href="{{ route('admin.vehicles.show', $driver->vehicle) }}" style="color: var(--accent); font-weight: 600;">
                                            {{ $driver->vehicle->vehicle_make }} {{ $driver->vehicle->vehicle_model }}
                                        </a>
                                        <div style="color: var(--muted); font-size: 0.9rem;">{{ $driver->vehicle->plate_number }}</div>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td data-label="Status"><span class="badge" data-status="{{ $driver->status }}">{{ $driver->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No drivers found for this supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
