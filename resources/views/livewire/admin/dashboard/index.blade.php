<section>
    <x-admin.header pageTitle="Dashboard" pageSubTitle="Live overview of operations and capacity." />

    <div id="bookingStatusData" data-labels='@json($bookingStatusLabels)' data-values='@json($bookingStatusValues)' hidden></div>
    <div id="vehicleStatusData" data-labels='@json($vehicleStatusLabels)' data-values='@json($vehicleStatusValues)' hidden></div>

    <div class="toolbar">
        <div>
            <label for="dashboard-start">Start date</label>
            <input id="dashboard-start" type="date" wire:model="startDate" />
        </div>
        <div>
            <label for="dashboard-end">End date</label>
            <input id="dashboard-end" type="date" wire:model="endDate" />
        </div>
        <button class="button secondary" type="button" wire:click="resetFilters">Reset</button>
        <button class="button" type="button" wire:click="exportBookingStatus">Export Booking Status</button>
        <button class="button secondary" type="button" wire:click="exportVehicleStatus">Export Vehicle Status</button>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Users</h3>
            <div class="metric">{{ $userCount }}</div>
        </div>
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
        <div class="card">
            <h3>Bookings</h3>
            <div class="metric">{{ $bookingCount }}</div>
        </div>
    </div>

    <div class="card-grid" style="margin-top: 18px;">
        <div class="card">
            <h3>Pending Bookings</h3>
            <div class="metric">{{ $pendingBookings }}</div>
        </div>
        <div class="card">
            <h3>Available Vehicles</h3>
            <div class="metric">{{ $availableVehicles }}</div>
        </div>
    </div>

    <div class="card-grid" style="margin-top: 24px;">
        <div class="card chart-card" wire:ignore>
            <h3>Bookings by Status</h3>
            <div class="chart-wrap">
                <canvas id="bookingStatusChart"></canvas>
            </div>
        </div>
        <div class="card chart-card" wire:ignore>
            <h3>Vehicles by Status</h3>
            <div class="chart-wrap">
                <canvas id="vehicleStatusChart"></canvas>
            </div>
        </div>
    </div>
</section>

<script>
    window.initZenoCharts = function () {
        const bookingData = document.getElementById('bookingStatusData');
        const vehicleData = document.getElementById('vehicleStatusData');
        const bookingLabels = bookingData ? JSON.parse(bookingData.dataset.labels || '[]') : [];
        const bookingValues = bookingData ? JSON.parse(bookingData.dataset.values || '[]') : [];
        const vehicleLabels = vehicleData ? JSON.parse(vehicleData.dataset.labels || '[]') : [];
        const vehicleValues = vehicleData ? JSON.parse(vehicleData.dataset.values || '[]') : [];

        if (window.ZenoCharts.bookingStatusChart) {
            window.ZenoCharts.bookingStatusChart.destroy();
        }
        if (window.ZenoCharts.vehicleStatusChart) {
            window.ZenoCharts.vehicleStatusChart.destroy();
        }

        const bookingCtx = document.getElementById('bookingStatusChart');
        const vehicleCtx = document.getElementById('vehicleStatusChart');

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
    };

    document.addEventListener('DOMContentLoaded', window.initZenoCharts);
</script>
