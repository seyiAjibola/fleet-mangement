<section>
    <x-admin.header pageTitle="Vehicle Details" pageSubTitle="Full vehicle profile, status, and specifications." />
    <x-admin.toast />

    <div class="card" style="display: grid; gap: 18px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">{{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }}</h3>
                <p style="margin: 6px 0 0; color: var(--muted);">{{ $vehicle->plate_number }} • {{ $vehicle->vehicle_category }}</p>
            </div>
            <div class="table-actions">
                <button class="button icon-button icon-upload" type="button" wire:click="openUploadModal" aria-label="Upload images" title="Upload images"><x-admin.icon name="upload" /></button>
                <a class="button secondary icon-button icon-edit" href="{{ route('admin.vehicles.edit', $vehicle) }}" aria-label="Edit vehicle" title="Edit vehicle"><x-admin.icon name="edit" /></a>
                <a class="button secondary icon-button icon-back" href="{{ route('admin.vehicles.index') }}" aria-label="Back to vehicles" title="Back to vehicles"><x-admin.icon name="back" /></a>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                <div>
                    <h3 style="margin-bottom: 4px;">Vehicle Gallery</h3>
                    <p style="margin: 0; color: var(--muted);">Upload multiple images and manage the primary cover image here.</p>
                </div>
                @if ($vehicle->images->isNotEmpty())
                    <span class="badge">{{ $vehicle->images->count() }} images</span>
                @endif
            </div>

            @if ($vehicle->images->isEmpty())
                <div class="vehicle-empty-state">
                    <div>
                        <strong>No vehicle images yet.</strong>
                        <p style="margin: 6px 0 0; color: var(--muted);">Use the upload modal to add a gallery without changing the vehicle form layout.</p>
                    </div>
                    <button class="button icon-button icon-upload" type="button" wire:click="openUploadModal" aria-label="Add images" title="Add images"><x-admin.icon name="upload" /></button>
                </div>
            @else
                <div class="vehicle-gallery">
                    @foreach ($vehicle->images as $image)
                        <article class="vehicle-gallery-item">
                            <div class="vehicle-gallery-media">
                                <button class="vehicle-gallery-trigger" type="button" wire:click="openPreviewModal({{ $image->id }})">
                                    <img src="{{ $image->publicPath() }}" alt="{{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }}" />
                                </button>
                            </div>
                            <div class="vehicle-gallery-meta">
                                <div style="display: flex; justify-content: space-between; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                                    <span class="badge">{{ $image->is_primary ? 'Primary' : 'Gallery' }}</span>
                                    <small style="color: var(--muted);">Image {{ $loop->iteration }}</small>
                                </div>
                                <div class="table-actions">
                                    @if (! $image->is_primary)
                                        <button class="button secondary icon-button icon-star" type="button" wire:click="setPrimaryImage({{ $image->id }})" aria-label="Make primary image" title="Make primary image"><x-admin.icon name="star" /></button>
                                    @endif
                                    <button class="button secondary icon-button icon-delete" type="button" wire:click="deleteImage({{ $image->id }})" onclick="return confirm('Delete this vehicle image?')" aria-label="Delete image" title="Delete image"><x-admin.icon name="delete" /></button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Status</h3>
                <div><span class="badge" data-status="{{ $vehicle->status }}">{{ $vehicle->status }}</span></div>
            </div>
            <div class="card">
                <h3>Condition</h3>
                <div>{{ ucfirst($vehicle->vehicle_condition) }}</div>
            </div>
            <div class="card">
                <h3>Fuel Type</h3>
                <div>{{ ucfirst($vehicle->fuel_type ?? '—') }}</div>
            </div>
            <div class="card">
                <h3>Supplier</h3>
                <div>{{ $vehicle->supplier?->business_name ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Assigned Drivers</h3>
                <div>{{ $vehicle->drivers->count() }}</div>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 14px; flex-wrap: wrap;">
                <div>
                    <h3 style="margin-bottom: 4px;">Assigned Drivers</h3>
                    <p style="margin: 0; color: var(--muted);">Open the full driver record directly from this vehicle page.</p>
                </div>
                <span class="badge">{{ $vehicle->drivers->count() }} drivers</span>
            </div>

            <div class="table-card" style="box-shadow: none; border: 1px solid var(--border);">
                <table>
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Phone Number</th>
                            <th>License Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicle->drivers as $driver)
                            <tr>
                                <td data-label="Driver">
                                    <a href="{{ route('admin.drivers.show', $driver) }}" style="color: var(--accent); font-weight: 600;">
                                        {{ $driver->driver_name }}
                                    </a>
                                </td>
                                <td data-label="Phone Number">{{ $driver->phone_number }}</td>
                                <td data-label="License Number">{{ $driver->license_number }}</td>
                                <td data-label="Status"><span class="badge" data-status="{{ $driver->status }}">{{ $driver->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No drivers are assigned to this vehicle.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-card">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 220px;">Vehicle Type</th>
                        <td>{{ $vehicle->vehicle_category }}</td>
                    </tr>
                    <tr>
                        <th>Vehicle Make</th>
                        <td>{{ $vehicle->vehicle_make }}</td>
                    </tr>
                    <tr>
                        <th>Vehicle Model</th>
                        <td>{{ $vehicle->vehicle_model }}</td>
                    </tr>
                    <tr>
                        <th>Vehicle Plate No</th>
                        <td>{{ $vehicle->plate_number }}</td>
                    </tr>
                    <tr>
                        <th>Year</th>
                        <td>{{ $vehicle->vehicle_year }}</td>
                    </tr>
                    <tr>
                        <th>Vehicle Color</th>
                        <td>{{ $vehicle->vehicle_color }}</td>
                    </tr>
                    <tr>
                        <th>Passenger Capacity</th>
                        <td>{{ $vehicle->passenger_capacity }}</td>
                    </tr>
                    <tr>
                        <th>Air Condition</th>
                        <td>{{ $vehicle->air_condition ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $vehicle->vehicle_location }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @if ($showUploadModal)
        <div class="admin-modal-backdrop" wire:click="closeUploadModal"></div>
        <div class="admin-modal-shell" role="dialog" aria-modal="true" aria-labelledby="vehicle-upload-title">
            <div class="admin-modal-card" wire:click.stop>
                <div class="admin-modal-header">
                    <div>
                        <h3 id="vehicle-upload-title" style="margin: 0;">Upload Vehicle Images</h3>
                        <p style="margin: 6px 0 0; color: var(--muted);">Upload multiple JPG, PNG, or WebP images. If no cover image exists, the first uploaded image becomes the primary image.</p>
                    </div>
                    <button class="button secondary icon-button icon-close" type="button" wire:click="closeUploadModal" aria-label="Close upload modal" title="Close upload modal"><x-admin.icon name="close" /></button>
                </div>

                <form class="form-card" style="padding: 0; border: none; box-shadow: none; background: transparent; max-width: 100%;" wire:submit.prevent="uploadImages">
                    <div class="form-row">
                        <label for="vehicle-images">Vehicle Images</label>
                        <input id="vehicle-images" type="file" wire:model="images" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple />
                        <small style="color: var(--muted);">Maximum file size is 5MB per image.</small>
                        @error('images') <p class="text-red-500">{{ $message }}</p> @enderror
                        @error('images.*') <p class="text-red-500">{{ $message }}</p> @enderror
                    </div>

                    @if ($images !== [])
                        <div class="vehicle-upload-preview">
                            @foreach ($images as $image)
                                <div class="vehicle-upload-preview-item">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Vehicle upload preview" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-actions" style="margin-top: 18px;">
                        <button class="button" type="submit">Upload Images</button>
                        <button class="button secondary" type="button" wire:click="closeUploadModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @php
        $previewImage = $previewImageId ? $vehicle->images->firstWhere('id', $previewImageId) : null;
    @endphp

    @if ($previewImage)
        <div class="admin-modal-backdrop" wire:click="closePreviewModal"></div>
        <div class="admin-modal-shell" role="dialog" aria-modal="true" aria-labelledby="vehicle-preview-title">
            <div class="admin-modal-card vehicle-preview-modal" wire:click.stop>
                <div class="admin-modal-header">
                    <div>
                        <h3 id="vehicle-preview-title" style="margin: 0;">Vehicle Image Preview</h3>
                        <p style="margin: 6px 0 0; color: var(--muted);">{{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }} • {{ $vehicle->plate_number }}</p>
                    </div>
                    <button class="button secondary icon-button icon-close" type="button" wire:click="closePreviewModal" aria-label="Close preview modal" title="Close preview modal"><x-admin.icon name="close" /></button>
                </div>

                <div class="vehicle-preview-media">
                    <img src="{{ $previewImage->publicPath() }}" alt="{{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }}" />
                </div>
            </div>
        </div>
    @endif
</section>
