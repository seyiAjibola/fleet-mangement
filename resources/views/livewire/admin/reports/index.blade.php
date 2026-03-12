<section>
    <x-admin.header pageTitle="Reports" pageSubTitle="Analytics and export center." />

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
    </div>

    <div class="card-grid">
        <div id="bookingSourceData" data-labels='@json($bookingSourceLabels)' data-values='@json($bookingSourceValues)'></div>
        <div id="vehicleCategoryData" data-labels='@json($vehicleCategoryLabels)' data-values='@json($vehicleCategoryValues)'></div>
        <div id="supplierTierData" data-labels='@json($supplierTierLabels)' data-values='@json($supplierTierValues)'></div>
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
