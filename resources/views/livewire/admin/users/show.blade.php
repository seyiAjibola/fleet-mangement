<section>
    <x-admin.header pageTitle="User Details" pageSubTitle="Full account profile and identity information." />

    <div class="card" style="display: grid; gap: 18px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">{{ $user->name }}</h3>
                <p style="margin: 6px 0 0; color: var(--muted);">{{ $user->email }}</p>
            </div>
            <div class="table-actions">
                <a class="button secondary icon-button icon-edit" href="{{ route('admin.users.edit', $user) }}" aria-label="Edit user" title="Edit user"><x-admin.icon name="edit" /></a>
                <a class="button secondary icon-button icon-back" href="{{ route('admin.users.index') }}" aria-label="Back to users" title="Back to users"><x-admin.icon name="back" /></a>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Role</h3>
                <div>{{ ucfirst($user->role) }}</div>
            </div>
            <div class="card">
                <h3>Email Verification</h3>
                <div>{{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}</div>
            </div>
            <div class="card">
                <h3>NIN</h3>
                <div>{{ $user->nin ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Created</h3>
                <div>{{ $user->created_at?->format('M j, Y g:i A') ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Last Updated</h3>
                <div>{{ $user->updated_at?->format('M j, Y g:i A') ?: '—' }}</div>
            </div>
        </div>

        <div class="table-card">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 220px;">User Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>NIN</th>
                        <td>{{ $user->nin ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ ucfirst($user->role) }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Email Verified At</th>
                        <td>{{ $user->email_verified_at?->format('M j, Y g:i A') ?: '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

            @if ($user)
                <div class="card">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                        <div>
                            <h3 style="margin-bottom: 4px;">Suppliers</h3>
                            <p style="margin: 0; color: var(--muted);">Open a supplier record directly and review their assigned vehicle.</p>
                        </div>
                        <span class="badge">{{ $user->suppliers->count() }} suppliers</span>
                    </div>
                    <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                        <table>
                            <thead>
                                <tr>
                                    <th>Suppliers</th>
                                    <th>Status</th>
                                    <th>City</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($user->suppliers as $supplier)
                                    <tr>
                                        <td data-label="Suppliers">
                                            <a href="{{ route('admin.suppliers.show', $supplier) }}" style="color: var(--accent); font-weight: 600;">
                                                {{ $supplier->business_name }}
                                            </a>
                                        </td>
                                        <td data-label="Status"><span class="badge" data-status="{{ $supplier->status }}">{{ $supplier->status }}</span></td>
                                        <td data-label="City">{{ $supplier->city }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No suppliers created.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                        <div>
                            <h3 style="margin-bottom: 4px;">Vehicles</h3>
                            <p style="margin: 0; color: var(--muted);">Open a vehicle record and review any assigned drivers.</p>
                        </div>
                        <span class="badge">{{ $user->vehicles->count() }} vehicles</span>
                    </div>
                    <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                        <table>
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Plate Number</th>
                                    <th>Category</th>
                                    <th>Drivers</th>
                                    <th>Status</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($user->vehicles as $vehicle)
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
                                        <td data-label="Supplier">{{ $vehicle->supplier->business_name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No vehicles created.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                        <div>
                            <h3 style="margin-bottom: 4px;">Drivers</h3>
                            <p style="margin: 0; color: var(--muted);">Open a driver record and  review their assigned vehicle.</p>
                        </div>
                        <span class="badge">{{ $user->drivers->count() }} drivers</span>
                    </div>
                    <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
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
                                @forelse ($user->drivers as $driver)
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
                                        <td colspan="5">No drivers created.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                        <table>
                            <thead>
                                <tr>
                                    <th>Bookings</th>
                                    <th>Pickup Time</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($user->bookings as $booking)
                                    <tr>
                                        <td data-label="Bookings">{{ $booking->customer_name }}</td>
                                        <td data-label="Pickup Time">{{ $booking->pickup_time }}</td>
                                        <td data-label="Vehicle">
                                            @if ($booking->vehicle)
                                                <a href="{{ route('admin.vehicles.show', $booking->vehicle) }}" style="color: var(--accent); font-weight: 600;">
                                                    {{ $booking->vehicle->vehicle_make }} {{ $booking->vehicle->vehicle_model }}
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td data-label="Driver">
                                            @if ($booking->driver)
                                                <a href="{{ route('admin.drivers.show', $booking->driver) }}" style="color: var(--accent); font-weight: 600;">
                                                    {{ $booking->driver->driver_name }}
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td data-label="Status"><span class="badge" data-status="{{ $booking->status }}">{{ $booking->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No bookings created.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
