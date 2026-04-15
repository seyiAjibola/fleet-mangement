<div @if (! $expanded) wire:poll.30s @endif>
    @if ($expanded)
        <div class="card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                <div>
                    <h3 style="margin-bottom: 4px;">Notifications</h3>
                    <p style="margin: 0; color: var(--muted);">Compliance alerts delivered to your workspace.</p>
                </div>
                <div class="table-actions">
                    @if ($unreadCount > 0)
                        <button class="button secondary" type="button" wire:click="markAllAsRead">Mark all as read</button>
                    @endif
                    <span class="badge">{{ $unreadCount }} unread</span>
                </div>
            </div>

            <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentNotifications as $notification)
                            <tr style="{{ $notification->read_at ? '' : 'background: rgba(15, 118, 110, 0.06);' }}">
                                <td data-label="Message">
                                    <div style="font-weight: 600;">{{ $notification->data['message'] ?? 'Notification' }}</div>
                                    <div style="color: var(--muted); font-size: 0.9rem;">{{ $notification->data['compliance_type'] ?? 'Compliance' }} • {{ $notification->data['entity_label'] ?? 'Entity' }}</div>
                                </td>
                                <td data-label="Status">
                                    <livewire:admin.compliance.compliance-badge :status="$notification->data['status'] ?? 'valid'" :key="'notification-badge-'.$notification->id" />
                                </td>
                                <td data-label="Received">{{ $notification->created_at?->diffForHumans() }}</td>
                                <td data-label="Actions" style="text-align: right;">
                                    @if (! $notification->read_at)
                                        <button class="button secondary" type="button" wire:click="markAsRead('{{ $notification->id }}')">Mark read</button>
                                    @else
                                        <span class="badge">Read</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No notifications yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <details class="admin-topbar-menu">
            <summary class="admin-topbar-trigger" style="position: relative; padding: 8px; min-width: 42px; justify-content: center;" aria-label="Notifications">
                <span style="display: inline-flex; width: 18px; height: 18px;">
                    <x-admin.icon name="bell" />
                </span>
                @if ($unreadCount > 0)
                    <span style="position: absolute; top: -4px; right: -4px; display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; padding: 0 6px; border-radius: 999px; background: #e11d48; color: #fff; font-size: 0.75rem; font-weight: 700; box-shadow: 0 0 0 3px rgba(255,255,255,0.95);">
                        {{ $unreadCount }}
                    </span>
                @endif
            </summary>
            <div class="admin-topbar-menu-items" style="width: min(420px, 90vw); padding: 0;">
                <div style="padding: 14px 16px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; gap: 12px; align-items: center;">
                    <strong>Notifications</strong>
                    @if ($unreadCount > 0)
                        <button type="button" wire:click="markAllAsRead" style="background: none; border: none; color: var(--accent); font-weight: 600; cursor: pointer;">Mark all read</button>
                    @endif
                </div>
                <div style="max-height: 380px; overflow: auto;">
                    @forelse ($recentNotifications as $notification)
                        <div style="padding: 14px 16px; border-bottom: 1px solid var(--border); background: {{ $notification->read_at ? 'transparent' : 'rgba(15, 118, 110, 0.06)' }};">
                            <div style="display: flex; justify-content: space-between; gap: 12px; align-items: start;">
                                <div>
                                    <div style="font-weight: 600; margin-bottom: 4px;">{{ $notification->data['message'] ?? 'Notification' }}</div>
                                    <div style="color: var(--muted); font-size: 0.9rem;">{{ $notification->data['compliance_type'] ?? 'Compliance' }} • {{ $notification->data['entity_label'] ?? 'Entity' }}</div>
                                    <div style="color: var(--muted); font-size: 0.82rem; margin-top: 6px;">{{ $notification->created_at?->diffForHumans() }}</div>
                                </div>
                                @if (! $notification->read_at)
                                    <button 
                                        class="button secondary" 
                                        type="button" 
                                        style="border-radius: 999px; padding: 6px 12px; font-size: 0.75rem;"
                                        wire:click="markAsRead('{{ $notification->id }}')">Read</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="padding: 16px; color: var(--muted);">No notifications yet.</div>
                    @endforelse
                </div>
                <div style="padding: 14px 16px; border-top: 1px solid var(--border);">
                    <a href="{{ route('admin.notifications.index') }}" style="color: var(--accent); font-weight: 600;">Open full notifications</a>
                </div>
            </div>
        </details>
    @endif
</div>
