<section>
    <x-admin.header pageTitle="Dashboard" pageSubTitle="Live overview of operations and capacity." />

    {{-- <div id="bookingStatusData" data-labels='@json($bookingStatusLabels)' data-values='@json($bookingStatusValues)' hidden></div> --}}
    <div id="vehicleStatusData" data-labels='@json($vehicleStatusLabels)' data-values='@json($vehicleStatusValues)' hidden></div>
    <div id="supplierTierData" data-labels='@json($supplierTierLabels)' data-values='@json($supplierTierValues)' hidden></div>

    <div class="toolbar">
        {{-- <div>
            <label for="dashboard-start">Start date</label>
            <input id="dashboard-start" type="date" wire:model="startDate" />
        </div>
        <div>
            <label for="dashboard-end">End date</label>
            <input id="dashboard-end" type="date" wire:model="endDate" />
        </div>
        <button class="button secondary" type="button" wire:click="resetFilters">Reset</button>
        <button class="button" type="button" wire:click="exportBookingStatus">Export Booking Status</button> --}}
        <button class="button secondary" type="button" wire:click="exportVehicleStatus">Export Vehicle Status</button>
    </div>

    <div style="display: grid; grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.9fr); gap: 24px; align-items: start;">
        <div>
            <div class="card-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                @if ($isAdmin)
                    <div class="card">
                        <h3>Users</h3>
                        <div class="metric">{{ $userCount }}</div>
                    </div>
                @endif
                <div class="card">
                    <h3>Suppliers</h3>
                    <div class="metric">{{ $supplierCount }}</div>
                </div>
                <div class="card">
                    <h3>Vehicles</h3>
                    <div class="metric">{{ $vehicleCount }}</div>
                </div>
                <div class="card">
                    <h3>Drivers</h3>
                    <div class="metric">{{ $driverCount }}</div>
                </div>
                {{-- <div class="card">
                    <h3>Bookings</h3>
                    <div class="metric">{{ $bookingCount }}</div>
                </div>
                <div class="card">
                    <h3>Pending Bookings</h3>
                    <div class="metric">{{ $pendingBookings }}</div>
                </div>
                <div class="card">
                    <h3>Confirmed Bookings</h3>
                    <div class="metric">{{ $confirmedBookings }}</div>
                </div>
                <div class="card">
                    <h3>Rejected Bookings</h3>
                    <div class="metric">{{ $rejectedBookings }}</div>
                </div>
                <div class="card">
                    <h3>Canceled Bookings</h3>
                    <div class="metric">{{ $canceledBookings }}</div>
                </div> --}}
                <div class="card">
                    <h3>Available Vehicles</h3>
                    <div class="metric">{{ $availableVehicles }}</div>
                </div>
            </div>
        </div>

        <div style="display: grid; gap: 18px;">
            {{-- <div class="card chart-card" wire:ignore>
                <h3>Bookings by Status</h3>
                <div class="chart-wrap">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div> --}}
            <div class="card chart-card" wire:ignore>
                <h3>Suppliers by Tier</h3>
                <div class="chart-wrap">
                    <canvas id="supplierTierChart"></canvas>
                </div>
            </div>
            <div class="card chart-card" wire:ignore>
                <h3>Vehicles by Status</h3>
                <div class="chart-wrap">
                    <canvas id="vehicleStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 960px) {
            section > div[style*='grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.9fr)'] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</section>

<script>
    window.initZenoCharts = function () {
        const bookingData = document.getElementById('bookingStatusData');
        const vehicleData = document.getElementById('vehicleStatusData');
        const supplierData = document.getElementById('supplierTierData');
        const bookingLabels = bookingData ? JSON.parse(bookingData.dataset.labels || '[]') : [];
        const bookingValues = bookingData ? JSON.parse(bookingData.dataset.values || '[]') : [];
        const vehicleLabels = vehicleData ? JSON.parse(vehicleData.dataset.labels || '[]') : [];
        const vehicleValues = vehicleData ? JSON.parse(vehicleData.dataset.values || '[]') : [];
        const supplierTierLabels = supplierData ? JSON.parse(supplierData.dataset.labels || '[]') : [];
        const supplierTierValues = supplierData ? JSON.parse(supplierData.dataset.values || '[]') : [];

        if (window.ZenoCharts.bookingStatusChart) {
            window.ZenoCharts.bookingStatusChart.destroy();
        }
        if (window.ZenoCharts.vehicleStatusChart) {
            window.ZenoCharts.vehicleStatusChart.destroy();
        }
        if (window.ZenoCharts.supplierTierChart) {
            window.ZenoCharts.supplierTierChart.destroy();
        }

        const bookingCtx = document.getElementById('bookingStatusChart');
        const vehicleCtx = document.getElementById('vehicleStatusChart');
        const supplierCtx = document.getElementById('supplierTierChart');

        if (bookingCtx) {
            window.ZenoCharts.bookingStatusChart = new window.Chart(bookingCtx, {
                type: 'doughnut',
                data: {
                    labels: bookingLabels,
                    datasets: [
                        {
                            data: bookingValues,
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
            window.ZenoCharts.vehicleStatusChart = new window.Chart(vehicleCtx, {
                type: 'bar',
                data: {
                    labels: vehicleLabels,
                    datasets: [
                        {
                            label: 'Vehicles',
                            data: vehicleValues,
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
