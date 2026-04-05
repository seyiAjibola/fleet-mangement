<section>
    <x-admin.header pageTitle="Drivers" pageSubTitle="Roster management and licensing status." />
    <x-admin.toast />

    <div class="toolbar" style="justify-content: space-between">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center">
            <div>
                <label for="driver-search">Search</label>
                <input id="driver-search" type="search" wire:model.defer="search" placeholder="Name, phone, license" />
            </div>
            <div>
                <label for="driver-status">Status</label>
                <select id="driver-status" wire:model.defer="status">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
        </div>
        <a class="button" href="{{ route('admin.drivers.create') }}">Add New Driver</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>License</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($drivers as $driver)
                    <tr>
                        <td data-label="Name">{{ $driver->driver_name }}</td>
                        <td data-label="Phone">{{ $driver->phone_number }}</td>
                        <td data-label="License">{{ $driver->license_number }}</td>
                        <td data-label="Status"><span class="badge" data-status="{{ $driver->status }}">{{ $driver->status }}</span></td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary icon-button icon-view" href="{{ route('admin.drivers.show', $driver) }}" aria-label="View driver" title="View driver"><x-admin.icon name="view" /></a>
                                <a class="button secondary icon-button icon-edit" href="{{ route('admin.drivers.edit', $driver) }}" aria-label="Edit driver" title="Edit driver"><x-admin.icon name="edit" /></a>
                                @if ($driver->status === 'active')
                                    <button class="button secondary icon-button icon-cancel" type="button" wire:click="deactivate({{ $driver->driver_id }})" onclick="return confirm('Make this driver inactive?')" aria-label="Make driver inactive" title="Make driver inactive"><x-admin.icon name="cancel" /></button>
                                @else
                                    <button class="button secondary icon-button icon-confirm" type="button" wire:click="activate({{ $driver->driver_id }})" onclick="return confirm('Make this driver active?')" aria-label="Make driver active" title="Make driver active"><x-admin.icon name="confirm" /></button>
                                @endif
                                <button class="button secondary icon-button icon-delete" type="button" wire:click="delete({{ $driver->driver_id }})" onclick="return confirm('Delete this driver?')" aria-label="Delete driver" title="Delete driver"><x-admin.icon name="delete" /></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No drivers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $drivers->links() }}
    </div>
</section>
