<section>
    <x-admin.header pageTitle="Reports" pageSubTitle="Analytics and export center." />

    <div id="bookingSourceData" data-labels='@json($bookingSourceLabels)' data-values='@json($bookingSourceValues)' hidden></div>
    <div id="vehicleCategoryData" data-labels='@json($vehicleCategoryLabels)' data-values='@json($vehicleCategoryValues)' hidden></div>
    <div id="supplierTierData" data-labels='@json($supplierTierLabels)' data-values='@json($supplierTierValues)' hidden></div>

    <div class="toolbar">
        <div>
            <label for="reports-start">Start date</label>
            <input id="reports-start" type="date" wire:model="startDate" />
        </div>
        <div>
            <label for="reports-end">End date</label>
            <input id="reports-end" type="date" wire:model="endDate" />
        </div>
        <button class="button secondary" type="button" wire:click="resetFilters">Reset</button>
        <button class="button" type="button" wire:click="exportBookingSources">Export Booking Sources</button>
        <button class="button secondary" type="button" wire:click="exportVehicleCategories">Export Vehicle Categories</button>
        <button class="button secondary" type="button" wire:click="exportSupplierTiers">Export Supplier Tiers</button>
        <button class="button secondary" type="button" wire:click="exportSupplierFleetOverview">Export Supplier Fleet Overview</button>
        <button class="button secondary" type="button" wire:click="exportVehicleDriverAssignments">Export Vehicle Driver Assignments</button>
        <button class="button secondary" type="button" wire:click="exportStaffOverview">Export Staff Overview</button>
    </div>

    <div class="card-grid">
        <div class="card chart-card" wire:ignore>
            <h3>Bookings by Source</h3>
            <div class="chart-wrap">
                <canvas id="bookingSourceChart"></canvas>
            </div>
        </div>
        <div class="card chart-card" wire:ignore>
            <h3>Vehicles by Category</h3>
            <div class="chart-wrap">
                <canvas id="vehicleCategoryChart"></canvas>
            </div>
        </div>
        <div class="card chart-card" wire:ignore>
            <h3>Suppliers by Tier</h3>
            <div class="chart-wrap">
                <canvas id="supplierTierChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
            <div>
                <h3 style="margin-bottom: 4px;">Staff Overview</h3>
                <p style="margin: 0; color: var(--muted);">See the operational footprint of each staff user across suppliers, vehicles, drivers, and bookings.</p>
            </div>
            <span class="badge">{{ $staffOverview->count() }} staff</span>
        </div>

        <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
            <table>
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Suppliers</th>
                        <th>Vehicles</th>
                        <th>Drivers</th>
                        <th>Bookings</th>
                        <th>Confirmed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staffOverview as $staff)
                        <tr>
                            <td data-label="Staff">{{ $staff->name }}<div style="color: var(--muted); font-size: 0.9rem;">{{ $staff->email }}</div></td>
                            <td data-label="Suppliers">{{ $staff->suppliers_count }}</td>
                            <td data-label="Vehicles">{{ $staff->vehicles_count }}</td>
                            <td data-label="Drivers">{{ $staff->drivers_count }}</td>
                            <td data-label="Bookings">{{ $staff->bookings_count }}</td>
                            <td data-label="Confirmed">{{ $staff->confirmed_bookings_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No staff report data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
            <div>
                <h3 style="margin-bottom: 4px;">Staff Specific Report</h3>
                <p style="margin: 0; color: var(--muted);">Pick a staff user to see the suppliers, vehicles, drivers, and bookings created by that staff member.</p>
            </div>
            @if ($selectedStaffReport)
                <span class="badge">{{ $selectedStaffReport->bookings_count }} bookings</span>
            @endif
        </div>

        <div class="toolbar" style="margin-bottom: 0;">
            <div>
                <label for="staff-report-id">Staff</label>
                <select id="staff-report-id" wire:model.live="staffReportId">
                    @foreach ($staffMembers as $staffMember)
                        <option value="{{ $staffMember->id }}">{{ $staffMember->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="button" type="button" wire:click="exportSelectedStaffReport" @disabled(! $selectedStaffReport)>
                Export Selected Staff Report
            </button>
        </div>

        @if ($selectedStaffReport)
            <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <tbody>
                        <tr>
                            <th style="width: 220px;">Staff</th>
                            <td>{{ $selectedStaffReport->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $selectedStaffReport->email }}</td>
                        </tr>
                        <tr>
                            <th>Suppliers</th>
                            <td>{{ $selectedStaffReport->suppliers_count }}</td>
                        </tr>
                        <tr>
                            <th>Vehicles</th>
                            <td>{{ $selectedStaffReport->vehicles_count }}</td>
                        </tr>
                        <tr>
                            <th>Drivers</th>
                            <td>{{ $selectedStaffReport->drivers_count }}</td>
                        </tr>
                        <tr>
                            <th>Bookings</th>
                            <td>{{ $selectedStaffReport->bookings_count }}</td>
                        </tr>
                        <tr>
                            <th>Confirmed Bookings</th>
                            <td>{{ $selectedStaffReport->confirmed_bookings_count }}</td>
                        </tr>
                    </tbody>
                </table>
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
                        @forelse ($selectedStaffReport->suppliers as $supplier)
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
                                <td colspan="3">No suppliers created by this staff user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <thead>
                        <tr>
                            <th>Vehicles</th>
                            <th>Supplier</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($selectedStaffReport->vehicles as $vehicle)
                            <tr>
                                <td data-label="Vehicles">
                                    <a href="{{ route('admin.vehicles.show', $vehicle) }}" style="color: var(--accent); font-weight: 600;">
                                        {{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }}
                                    </a>
                                    <div style="color: var(--muted); font-size: 0.9rem;">{{ $vehicle->plate_number }}</div>
                                </td>
                                <td data-label="Supplier">{{ $vehicle->supplier?->business_name ?: '—' }}</td>
                                <td data-label="Status"><span class="badge" data-status="{{ $vehicle->status }}">{{ $vehicle->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No vehicles created by this staff user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <thead>
                        <tr>
                            <th>Drivers</th>
                            <th>Supplier</th>
                            <th>Assigned Vehicle</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($selectedStaffReport->drivers as $driver)
                            <tr>
                                <td data-label="Drivers">
                                    <a href="{{ route('admin.drivers.show', $driver) }}" style="color: var(--accent); font-weight: 600;">
                                        {{ $driver->driver_name }}
                                    </a>
                                </td>
                                <td data-label="Supplier">{{ $driver->supplier?->business_name ?: '—' }}</td>
                                <td data-label="Assigned Vehicle">
                                    @if ($driver->vehicle)
                                        <a href="{{ route('admin.vehicles.show', $driver->vehicle) }}" style="color: var(--accent); font-weight: 600;">
                                            {{ $driver->vehicle->vehicle_make }} {{ $driver->vehicle->vehicle_model }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td data-label="Status"><span class="badge" data-status="{{ $driver->status }}">{{ $driver->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No drivers created by this staff user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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
                        @forelse ($selectedStaffReport->bookings as $booking)
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
                                <td colspan="5">No bookings created by this staff user in the selected range.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card" style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
            <div>
                <h3 style="margin-bottom: 4px;">Supplier Specific Report</h3>
                <p style="margin: 0; color: var(--muted);">Pick one supplier and export the number of cars under that supplier.</p>
            </div>
            @if ($selectedSupplierReport)
                <span class="badge">{{ $selectedSupplierReport->vehicles_count }} cars</span>
            @endif
        </div>

        <div class="toolbar" style="margin-bottom: 0;">
            <div>
                <label for="supplier-report-id">Supplier</label>
                <select id="supplier-report-id" wire:model.live="supplierReportId">
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->supplier_id }}">{{ $supplier->business_name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="button" type="button" wire:click="exportSelectedSupplierCars" @disabled(! $selectedSupplierReport)>
                Export Selected Supplier Cars
            </button>
        </div>

        @if ($selectedSupplierReport)
            <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <tbody>
                        <tr>
                            <th style="width: 220px;">Supplier</th>
                            <td>
                                <a href="{{ route('admin.suppliers.show', $selectedSupplierReport) }}" style="color: var(--accent); font-weight: 600;">
                                    {{ $selectedSupplierReport->business_name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Number of Cars</th>
                            <td>{{ $selectedSupplierReport->vehicles_count }}</td>
                        </tr>
                        <tr>
                            <th>Number of Drivers</th>
                            <td>{{ $selectedSupplierReport->drivers_count }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><span class="badge" data-status="{{ $selectedSupplierReport->status }}">{{ $selectedSupplierReport->status }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <thead>
                        <tr>
                            <th>Supplier Cars</th>
                            <th>Plate Number</th>
                            <th>Assigned Drivers</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($selectedSupplierReport->vehicles as $vehicle)
                            <tr>
                                <td data-label="Supplier Cars">
                                    <a href="{{ route('admin.vehicles.show', $vehicle) }}" style="color: var(--accent); font-weight: 600;">
                                        {{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }}
                                    </a>
                                </td>
                                <td data-label="Plate Number">{{ $vehicle->plate_number }}</td>
                                <td data-label="Assigned Drivers">
                                    @if ($vehicle->drivers->isEmpty())
                                        —
                                    @else
                                        @foreach ($vehicle->drivers as $driver)
                                            <div>
                                                <a href="{{ route('admin.drivers.show', $driver) }}" style="color: var(--accent); font-weight: 600;">
                                                    {{ $driver->driver_name }}
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                                <td data-label="Status"><span class="badge" data-status="{{ $vehicle->status }}">{{ $vehicle->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No cars found for this supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-card" style="margin-top: 18px; box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <thead>
                        <tr>
                            <th>Assigned Drivers</th>
                            <th>Phone Number</th>
                            <th>Assigned Vehicle</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($selectedSupplierReport->drivers as $driver)
                            <tr>
                                <td data-label="Assigned Drivers">
                                    <a href="{{ route('admin.drivers.show', $driver) }}" style="color: var(--accent); font-weight: 600;">
                                        {{ $driver->driver_name }}
                                    </a>
                                </td>
                                <td data-label="Phone Number">{{ $driver->phone_number }}</td>
                                <td data-label="Assigned Vehicle">
                                    @if ($driver->vehicle)
                                        <a href="{{ route('admin.vehicles.show', $driver->vehicle) }}" style="color: var(--accent); font-weight: 600;">
                                            {{ $driver->vehicle->vehicle_make }} {{ $driver->vehicle->vehicle_model }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td data-label="Status"><span class="badge" data-status="{{ $driver->status }}">{{ $driver->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No drivers assigned under this supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card" style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
            <div>
                <h3 style="margin-bottom: 4px;">Supplier Fleet Overview</h3>
                <p style="margin: 0; color: var(--muted);">Review each supplier’s vehicle and driver footprint from one report.</p>
            </div>
            <span class="badge">{{ $supplierFleetOverview->count() }} suppliers</span>
        </div>

        <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
            <table>
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Vehicles</th>
                        <th>Drivers</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplierFleetOverview as $supplier)
                        <tr>
                            <td data-label="Supplier">
                                <a href="{{ route('admin.suppliers.show', $supplier) }}" style="color: var(--accent); font-weight: 600;">
                                    {{ $supplier->business_name }}
                                </a>
                            </td>
                            <td data-label="Vehicles">{{ $supplier->vehicles_count }}</td>
                            <td data-label="Drivers">{{ $supplier->drivers_count }}</td>
                            <td data-label="Status"><span class="badge" data-status="{{ $supplier->status }}">{{ $supplier->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No supplier fleet data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
            <div>
                <h3 style="margin-bottom: 4px;">Vehicle Driver Assignments</h3>
                <p style="margin: 0; color: var(--muted);">See every vehicle with its assigned drivers and jump directly into the detail pages.</p>
            </div>
            <span class="badge">{{ $vehicleDriverAssignments->count() }} vehicles</span>
        </div>

        <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
            <table>
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Supplier</th>
                        <th>Assigned Drivers</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicleDriverAssignments as $vehicle)
                        <tr>
                            <td data-label="Vehicle">
                                <a href="{{ route('admin.vehicles.show', $vehicle) }}" style="color: var(--accent); font-weight: 600;">
                                    {{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }}
                                </a>
                                <div style="color: var(--muted); font-size: 0.9rem;">{{ $vehicle->plate_number }}</div>
                            </td>
                            <td data-label="Supplier">
                                @if ($vehicle->supplier)
                                    <a href="{{ route('admin.suppliers.show', $vehicle->supplier) }}" style="color: var(--accent); font-weight: 600;">
                                        {{ $vehicle->supplier->business_name }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td data-label="Assigned Drivers">
                                @if ($vehicle->drivers->isEmpty())
                                    —
                                @else
                                    @foreach ($vehicle->drivers as $driver)
                                        <div>
                                            <a href="{{ route('admin.drivers.show', $driver) }}" style="color: var(--accent); font-weight: 600;">
                                                {{ $driver->driver_name }}
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td data-label="Status"><span class="badge" data-status="{{ $vehicle->status }}">{{ $vehicle->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No vehicle assignment data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    window.initZenoCharts = function () {
        const bookingData = document.getElementById('bookingSourceData');
        const vehicleData = document.getElementById('vehicleCategoryData');
        const supplierData = document.getElementById('supplierTierData');
        const bookingSourceLabels = bookingData ? JSON.parse(bookingData.dataset.labels || '[]') : [];
        const bookingSourceValues = bookingData ? JSON.parse(bookingData.dataset.values || '[]') : [];
        const vehicleCategoryLabels = vehicleData ? JSON.parse(vehicleData.dataset.labels || '[]') : [];
        const vehicleCategoryValues = vehicleData ? JSON.parse(vehicleData.dataset.values || '[]') : [];
        const supplierTierLabels = supplierData ? JSON.parse(supplierData.dataset.labels || '[]') : [];
        const supplierTierValues = supplierData ? JSON.parse(supplierData.dataset.values || '[]') : [];

        if (window.ZenoCharts.bookingSourceChart) {
            window.ZenoCharts.bookingSourceChart.destroy();
        }
        if (window.ZenoCharts.vehicleCategoryChart) {
            window.ZenoCharts.vehicleCategoryChart.destroy();
        }
        if (window.ZenoCharts.supplierTierChart) {
            window.ZenoCharts.supplierTierChart.destroy();
        }

        const bookingCtx = document.getElementById('bookingSourceChart');
        const vehicleCtx = document.getElementById('vehicleCategoryChart');
        const supplierCtx = document.getElementById('supplierTierChart');

        if (bookingCtx) {
            window.ZenoCharts.bookingSourceChart = new window.Chart(bookingCtx, {
                type: 'pie',
                data: {
                    labels: bookingSourceLabels,
                    datasets: [
                        {
                            data: bookingSourceValues,
                            backgroundColor: ['#0f766e', '#f59e0b', '#1d4ed8', '#e11d48'],
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    },
                },
            });
        }

        if (vehicleCtx) {
            window.ZenoCharts.vehicleCategoryChart = new window.Chart(vehicleCtx, {
                type: 'bar',
                data: {
                    labels: vehicleCategoryLabels,
                    datasets: [
                        {
                            label: 'Vehicles',
                            data: vehicleCategoryValues,
                            backgroundColor: '#0f766e',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: { beginAtZero: true },
                    },
                },
            });
        }

        if (supplierCtx) {
            window.ZenoCharts.supplierTierChart = new window.Chart(supplierCtx, {
                type: 'doughnut',
                data: {
                    labels: supplierTierLabels,
                    datasets: [
                        {
                            data: supplierTierValues,
                            backgroundColor: ['#0f766e', '#f59e0b', '#1d4ed8', '#e11d48'],
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    },
                },
            });
        }
    };

    document.addEventListener('DOMContentLoaded', window.initZenoCharts);
</script>
