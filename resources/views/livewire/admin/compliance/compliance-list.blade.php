<div class="space-y-4">

    <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
        <div>
            <h3 style="margin-bottom: 4px;">Compliance</h3>
            <p style="margin: 0; color: var(--muted);">Compliance documents.</p>
        </div>
        <button wire:click="create"
            class="button">
            Add Compliance
        </button>
    </div>

    <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse ($records as $record)
            <tr>
                <td data-label="Type">
                    {{ $record->complianceType->name }}
                </td>
                <td data-label="Expiry Date">{{ optional($record->expiry_date)->format('Y-m-d') ?? 'N/A' }}</td>
                <td data-label="Status">
                    <livewire:admin.compliance.compliance-badge :status="$record->status" :key="'compliance-badge-'.$record->id" />
                </td>
                <td>
                    <button wire:click="edit({{ $record->id }})"
                        class="button">
                        Edit
                    </button>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="4"> No compliance records found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
