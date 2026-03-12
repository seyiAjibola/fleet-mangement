<section>
    <x-admin.header pageTitle="Users" pageSubTitle="Manage platform access and profiles." />
    <x-admin.toast />

    <div class="toolbar" style="justify-content: space-between">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center">
            <div>
                <label for="user-search">Search</label>
                <input id="user-search" type="search" wire:model.defer="search" placeholder="Name or email" />
            </div>
            <button class="button secondary" type="button" wire:click="applyFilters">Filter</button>
        </div>
        <a class="button" href="{{ route('admin.users.create') }}">Create user</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td data-label="Name">{{ $user->name }}</td>
                        <td data-label="Email">{{ $user->email }}</td>
                        <td>
                            <div class="table-actions">
                                <a class="button secondary" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                                <button class="button secondary" type="button" wire:click="delete({{ $user->id }})" onclick="return confirm('Delete this user?')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</section>
