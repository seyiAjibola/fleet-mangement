<div>
    @if($showModal)
        <div class="admin-modal-backdrop" wire:click="close"></div>
        <div class="admin-modal-shell" role="dialog" aria-modal="true" aria-labelledby="compliance-form-title">
            <div class="admin-modal-card" wire:click.stop style="max-width: 760px;">
                <div class="admin-modal-header">
                    <div>
                        <h3 id="compliance-form-title" style="margin: 0;">{{ $recordId ? 'Edit Compliance' : 'Add Compliance' }}</h3>
                        <p style="margin: 6px 0 0; color: var(--muted);">Track compliance details, supporting documents, and recent activity for this entity.</p>
                    </div>
                    <button class="button secondary icon-button icon-close" type="button" wire:click="close" aria-label="Close compliance modal" title="Close compliance modal"><x-admin.icon name="close" /></button>
                </div>

                <form class="form-card" style="padding: 0; border: none; box-shadow: none; background: transparent; max-width: 100%;" wire:submit.prevent="save">
                    <div class="form-row">
                        <label for="compliance-type">Compliance Type</label>
                        <select id="compliance-type" wire:model="compliance_type_id">
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('compliance_type_id') <p class="text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row form-row-split">
                        <div>
                            <label for="compliance-document-number">Document Number</label>
                            <input id="compliance-document-number" type="text" wire:model="document_number" placeholder="Document Number" />
                            @error('document_number') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="compliance-issued-date">Issued Date</label>
                            <input id="compliance-issued-date" type="date" wire:model="issued_date" />
                            @error('issued_date') <p class="text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="compliance-expiry-date">Expiry Date</label>
                        <input id="compliance-expiry-date" type="date" wire:model="expiry_date" />
                        @error('expiry_date') <p class="text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row">
                        <label for="compliance-documents">Supporting Documents</label>
                        <input id="compliance-documents" type="file" wire:model="documents" multiple accept=".pdf,.jpg,.jpeg,.png,.webp" />
                        <small style="color: var(--muted);">Upload PDFs or images up to 5MB each.</small>
                        @error('documents') <p class="text-red-500">{{ $message }}</p> @enderror
                        @error('documents.*') <p class="text-red-500">{{ $message }}</p> @enderror
                    </div>

                    @if ($existingDocuments->isNotEmpty())
                        <div class="form-row">
                            <label>Attached Documents</label>
                            <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
                                <table>
                                    <tbody>
                                        @foreach ($existingDocuments as $document)
                                            <tr>
                                                <td>
                                                    <a href="{{ $document->publicPath() }}" target="_blank" rel="noopener" style="color: var(--accent); font-weight: 600;">
                                                        {{ strtoupper($document->file_type ?: 'file') }}
                                                    </a>
                                                </td>
                                                <td>{{ $document->created_at?->format('Y-m-d H:i') }}</td>
                                                <td style="text-align: right;">
                                                    <button wire:click="removeDocument({{ $document->id }})" type="button" class="button secondary">Remove</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($auditLogs->isNotEmpty())
                        <div class="form-row">
                            <label>Activity History</label>
                            <div class="table-card" style="box-shadow: none; border: 1px solid var(--border); max-height: 220px; overflow: auto;">
                                <table>
                                    <tbody>
                                        @foreach ($auditLogs as $log)
                                            <tr>
                                                <td>
                                                    <div style="font-weight: 600;">{{ $log->summary }}</div>
                                                    <div style="color: var(--muted); font-size: 0.9rem;">
                                                        {{ $log->actor?->name ?? 'System' }}
                                                        @if (($log->meta['source'] ?? null) === 'compliance_check')
                                                            • Scheduled compliance check
                                                        @endif
                                                    </div>
                                                </td>
                                                <td style="white-space: nowrap;">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="form-actions" style="margin-top: 18px;">
                        <button class="button" type="submit">Save</button>
                        <button class="button secondary" type="button" wire:click="close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
