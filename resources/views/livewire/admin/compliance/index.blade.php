<section>
    <x-admin.header pageTitle="Compliance" pageSubTitle="Compliance dashboard, filters, and exception monitoring." />

    <div class="toolbar">
        <div>
            <label for="compliance-entity-type">Entity type</label>
            <select id="compliance-entity-type" wire:model.live="entityType">
                <option value="">All entities</option>
                @foreach ($entityOptions as $option)
                    <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="compliance-status">Status</label>
            <select id="compliance-status" wire:model.live="status">
                <option value="">All statuses</option>
                <option value="valid">Valid</option>
                <option value="expiring">Expiring</option>
                <option value="expired">Expired</option>
                <option value="non_compliant">Non-compliant</option>
            </select>
        </div>
        <div>
            <label for="compliance-search">Search</label>
            <input id="compliance-search" type="text" wire:model.live.debounce.300ms="search" placeholder="Entity, type, or document" />
        </div>
        <label style="display: inline-flex; align-items: center; gap: 10px; margin-top: 24px;">
            <input type="checkbox" wire:model.live="expiringSoonOnly" />
            <span>Exceptions only</span>
        </label>
        <button class="button secondary" type="button" wire:click="resetFilters">Reset</button>
        <button class="button" type="button" wire:click="exportSummary">Export Summary</button>
        <button class="button secondary" type="button" wire:click="exportExceptions">Export Exceptions</button>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Total Records</h3>
            <div class="metric">{{ $totalRecords }}</div>
        </div>
        <div class="card">
            <h3>Compliant %</h3>
            <div class="metric">{{ $compliantPercentage }}%</div>
        </div>
        <div class="card">
            <h3>Expiring Soon</h3>
            <div class="metric">{{ $expiringCount }}</div>
        </div>
        <div class="card">
            <h3>Expired</h3>
            <div class="metric">{{ $expiredCount }}</div>
        </div>
        <div class="card">
            <h3>Non-Compliant</h3>
            <div class="metric">{{ $nonCompliantCount }}</div>
        </div>
    </div>

    <div class="card" style="margin-top: 24px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
            <div>
                <h3 style="margin-bottom: 4px;">Tracked Records</h3>
                <p style="margin: 0; color: var(--muted);">Review expiring, expired, and compliant documents across the fleet.</p>
            </div>
            <span class="badge">{{ $records->count() }} shown</span>
        </div>

        <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
            <table>
                <thead>
                    <tr>
                        <th>Entity</th>
                        <th>Type</th>
                        <th>Document</th>
                        <th>Expiry</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td data-label="Entity">
                                <a href="{{ $this->entityUrl($record) }}" style="color: var(--accent); font-weight: 600;">
                                    {{ $this->entityLabel($record) }}
                                </a>
                                <div style="color: var(--muted); font-size: 0.9rem;">{{ ucfirst($record->entity_type) }}</div>
                            </td>
                            <td data-label="Type">{{ $record->complianceType?->name ?: '—' }}</td>
                            <td data-label="Document">{{ $record->document_number ?: '—' }}</td>
                            <td data-label="Expiry">{{ optional($record->expiry_date)->format('Y-m-d') ?? 'N/A' }}</td>
                            <td data-label="Status">
                                <livewire:admin.compliance.compliance-badge :status="$record->status" :key="'dashboard-compliance-badge-'.$record->id" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No compliance records match the current filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
