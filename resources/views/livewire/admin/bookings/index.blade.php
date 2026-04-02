<section>
    <x-admin.header pageTitle="Customer Bookings" pageSubTitle="Track pickup windows and fulfillment status." />
    <x-admin.toast />

    <div class="toolbar" style="justify-content: end">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center">
            <div>
                <label for="booking-search">Search</label>
                <input id="booking-search" type="search" wire:model.defer="search" placeholder="Customer, phone, pickup, dropoff" />
            </div>
            <div>
                <label for="booking-status">Status</label>
                <select id="booking-status" wire:model.defer="status">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label for="booking-start">Start date</label>
                <input id="booking-start" type="date" wire:model.defer="startDate" />
            </div>
            <div>
                <label for="booking-end">End date</label>
                <input id="booking-end" type="date" wire:model.defer="endDate" />
            </div>
            <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
            <button class="button secondary" type="button" wire:click="resetFilters">Reset</button>
        </div>
        <a class="button" href="{{ route('admin.bookings.create') }}">Add New Booking</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                    <th>Time</th>
                    <th>Return</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td data-label="Customer">{{ $booking->customer_name }}</td>
                        <td data-label="Pickup">{{ $booking->pickup_location }}</td>
                        <td data-label="Dropoff">{{ $booking->dropoff_location }}</td>
                        <td data-label="Time">{{ $booking->pickup_time }}</td>
                        <td data-label="Return">{{ $booking->dropoff_time ?? '—' }}</td>
                        <td data-label="Status"><span class="badge">{{ $booking->status }}</span></td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary" href="{{ route('admin.bookings.show', $booking) }}">View</a>
                                <a class="button secondary" href="{{ route('admin.bookings.edit', $booking) }}">Edit</a>
                                <button class="button secondary" type="button" wire:click="confirmBooking({{ $booking->booking_id }})">Confirm</button>
                                <button class="button secondary" type="button" wire:click="rejectBooking({{ $booking->booking_id }})">Reject</button>
                                <button class="button secondary" type="button" wire:click="cancelBooking({{ $booking->booking_id }})">Cancel</button>
                                <button class="button secondary" type="button" wire:click="delete({{ $booking->booking_id }})" onclick="return confirm('Delete this booking?')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $bookings->links() }}
    </div>
</section>
