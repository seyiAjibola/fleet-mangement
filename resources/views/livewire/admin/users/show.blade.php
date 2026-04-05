<section>
    <x-admin.header pageTitle="User Details" pageSubTitle="Full account profile and identity information." />

    <div class="card" style="display: grid; gap: 18px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">{{ $user->name }}</h3>
                <p style="margin: 6px 0 0; color: var(--muted);">{{ $user->email }}</p>
            </div>
            <div class="table-actions">
                <a class="button secondary icon-button icon-edit" href="{{ route('admin.users.edit', $user) }}" aria-label="Edit user" title="Edit user"><x-admin.icon name="edit" /></a>
                <a class="button secondary icon-button icon-back" href="{{ route('admin.users.index') }}" aria-label="Back to users" title="Back to users"><x-admin.icon name="back" /></a>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Role</h3>
                <div>{{ ucfirst($user->role) }}</div>
            </div>
            <div class="card">
                <h3>Email Verification</h3>
                <div>{{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}</div>
            </div>
            <div class="card">
                <h3>NIN</h3>
                <div>{{ $user->nin ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Created</h3>
                <div>{{ $user->created_at?->format('M j, Y g:i A') ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Last Updated</h3>
                <div>{{ $user->updated_at?->format('M j, Y g:i A') ?: '—' }}</div>
            </div>
        </div>

        <div class="table-card">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 220px;">User Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>NIN</th>
                        <td>{{ $user->nin ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ ucfirst($user->role) }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Email Verified At</th>
                        <td>{{ $user->email_verified_at?->format('M j, Y g:i A') ?: '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
