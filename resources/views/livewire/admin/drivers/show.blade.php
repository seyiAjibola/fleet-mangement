<section>
    <x-admin.header pageTitle="Driver Details" pageSubTitle="Full driver profile, assignment, and compliance information." />

    <div class="card" style="display: grid; gap: 18px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">{{ $driver->driver_name }}</h3>
                <p style="margin: 6px 0 0; color: var(--muted);">{{ $driver->phone_number }}</p>
            </div>
            <div class="table-actions">
                <a class="button secondary icon-button icon-edit" href="{{ route('admin.drivers.edit', $driver) }}" aria-label="Edit driver" title="Edit driver"><x-admin.icon name="edit" /></a>
                <a class="button secondary icon-button icon-back" href="{{ route('admin.drivers.index') }}" aria-label="Back to drivers" title="Back to drivers"><x-admin.icon name="back" /></a>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Status</h3>
                <div><span class="badge" data-status="{{ $driver->status }}">{{ $driver->status }}</span></div>
            </div>
            <div class="card">
                <h3>Supplier</h3>
                <div>{{ $driver->supplier?->business_name ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Assigned Vehicle</h3>
                <div>{{ $driver->vehicle?->plate_number ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Experience</h3>
                <div>{{ $driver->years_experience }} years</div>
            </div>
        </div>

        <div class="card">
            <livewire:admin.compliance.compliance-list :entity="$driver" />
            <livewire:admin.compliance.compliance-form />
        </div>

        <div class="table-card">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 220px;">Driver Name</th>
                        <td>{{ $driver->driver_name }}</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{ $driver->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>Years Experience</th>
                        <td>{{ $driver->years_experience }}</td>
                    </tr>
                    <tr>
                        <th>Languages</th>
                        <td>{{ $driver->languages }}</td>
                    </tr>
                    <tr>
                        <th>Supplier</th>
                        <td>{{ $driver->supplier?->business_name ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Assigned Vehicle</th>
                        <td>{{ $driver->vehicle ? $driver->vehicle->vehicle_make . ' ' . $driver->vehicle->vehicle_model . ' (' . $driver->vehicle->plate_number . ')' : '—' }}</td>
                    </tr>
                    <tr>
                        <th>Professional Experience</th>
                        <td>{{ $driver->professional_experience ?: '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
