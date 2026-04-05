<section>
    <x-admin.header pageTitle="Users" pageSubTitle="Manage platform access and profiles." />
    <x-admin.toast />

    <div class="toolbar" style="justify-content: space-between">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: end; flex-wrap: wrap">
            <div>
                <label for="user-name">User Name</label>
                <input id="user-name" type="search" wire:model.defer="userName" placeholder="User name" />
            </div>
            <div>
                <label for="user-nin">NIN</label>
                <input id="user-nin" type="search" wire:model.defer="nin" placeholder="NIN" />
            </div>
            <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
            <button class="button secondary" type="button" wire:click="resetFilters">Reset</button>
        </div>
        <a class="button" href="{{ route('admin.users.create') }}">Add New User</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>NIN</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td data-label="Name">{{ $user->name }}</td>
                        <td data-label="Role">{{ ucfirst($user->role) }}</td>
                        <td data-label="NIN">{{ $user->nin ?: '—' }}</td>
                        <td data-label="Email">{{ $user->email }}</td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary icon-button icon-view" href="{{ route('admin.users.show', $user) }}" aria-label="View user" title="View user"><x-admin.icon name="view" /></a>
                                <a class="button secondary icon-button icon-edit" href="{{ route('admin.users.edit', $user) }}" aria-label="Edit user" title="Edit user"><x-admin.icon name="edit" /></a>
                                <button class="button secondary icon-button icon-delete" type="button" wire:click="delete({{ $user->id }})" onclick="return confirm('Delete this user?')" aria-label="Delete user" title="Delete user"><x-admin.icon name="delete" /></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</section>
