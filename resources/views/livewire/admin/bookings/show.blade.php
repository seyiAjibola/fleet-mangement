<section>
    <x-admin.header pageTitle="Booking Details" pageSubTitle="Full booking information, schedule, and assignment status." />

    <div class="card" style="display: grid; gap: 18px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">{{ $booking->customer_name }}</h3>
                <p style="margin: 6px 0 0; color: var(--muted);">{{ $booking->pickup_location }} to {{ $booking->dropoff_location }}</p>
            </div>
            <div class="table-actions">
                <a class="button secondary" href="{{ route('admin.bookings.edit', $booking) }}">Edit Booking</a>
                <a class="button secondary" href="{{ route('admin.bookings.index') }}">Back to Bookings</a>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Status</h3>
                <div><span class="badge">{{ $booking->status }}</span></div>
            </div>
            <div class="card">
                <h3>Vehicle Category</h3>
                <div>{{ $booking->vehicle_category }}</div>
            </div>
            <div class="card">
                <h3>Assigned Vehicle</h3>
                <div>{{ $booking->vehicle?->plate_number ?: 'Unassigned' }}</div>
            </div>
            <div class="card">
                <h3>Assigned Driver</h3>
                <div>{{ $booking->driver?->driver_name ?: 'Unassigned' }}</div>
            </div>
        </div>

        <div class="table-card">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 220px;">Customer Name</th>
                        <td>{{ $booking->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Customer Phone</th>
                        <td>{{ $booking->customer_phone }}</td>
                    </tr>
                    <tr>
                        <th>Pickup Location</th>
                        <td>{{ $booking->pickup_location }}</td>
                    </tr>
                    <tr>
                        <th>Dropoff Location</th>
                        <td>{{ $booking->dropoff_location }}</td>
                    </tr>
                    <tr>
                        <th>Pickup Time</th>
                        <td>{{ $booking->pickup_time }}</td>
                    </tr>
                    <tr>
                        <th>Dropoff Time</th>
                        <td>{{ $booking->dropoff_time ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Booking Source</th>
                        <td>{{ $booking->booking_source }}</td>
                    </tr>
                    <tr>
                        <th>Vehicle Category</th>
                        <td>{{ $booking->vehicle_category }}</td>
                    </tr>
                    <tr>
                        <th>Assigned Vehicle</th>
                        <td>{{ $booking->vehicle ? $booking->vehicle->vehicle_make . ' ' . $booking->vehicle->vehicle_model . ' (' . $booking->vehicle->plate_number . ')' : 'Unassigned' }}</td>
                    </tr>
                    <tr>
                        <th>Assigned Driver</th>
                        <td>{{ $booking->driver ? $booking->driver->driver_name . ' (' . $booking->driver->phone_number . ')' : 'Unassigned' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
