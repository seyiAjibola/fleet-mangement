<?php

namespace App\Livewire\Admin\Vehicles;

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Admin - Vehicle Details')]
class Show extends Component
{
    use WithFileUploads;

    public Vehicle $vehicle;
    public array $images = [];
    public bool $showUploadModal = false;
    public ?int $previewImageId = null;

    public function mount(Vehicle $vehicle): void
    {
        $this->vehicle = $vehicle->load(['supplier', 'images']);
        $this->showUploadModal = (bool) session('open_vehicle_upload_modal', false);
    }

    protected function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function openUploadModal(): void
    {
        $this->showUploadModal = true;
    }

    public function closeUploadModal(): void
    {
        $this->showUploadModal = false;
        $this->reset('images');
        $this->resetValidation();
    }

    public function openPreviewModal(int $imageId): void
    {
        $this->previewImageId = $this->vehicle->images()->whereKey($imageId)->value('id');
    }

    public function closePreviewModal(): void
    {
        $this->previewImageId = null;
    }

    public function uploadImages(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $nextSortOrder = ((int) $this->vehicle->images()->max('sort_order')) + 1;
            $hasPrimaryImage = $this->vehicle->images()->where('is_primary', true)->exists();

            foreach ($validated['images'] as $index => $image) {
                $path = $image->store('vehicles/' . $this->vehicle->vehicle_id, 'public');

                $this->vehicle->images()->create([
                    'path' => $path,
                    'is_primary' => ! $hasPrimaryImage && $index === 0,
                    'sort_order' => $nextSortOrder + $index,
                ]);
            }
        });

        $this->vehicle->load(['supplier', 'images']);
        $this->closeUploadModal();
        session()->flash('success', 'Vehicle images uploaded.');
    }

    public function setPrimaryImage(int $imageId): void
    {
        $image = $this->vehicle->images()->whereKey($imageId)->firstOrFail();

        DB::transaction(function () use ($image) {
            $this->vehicle->images()->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);
        });

        $this->vehicle->load(['supplier', 'images']);
        session()->flash('success', 'Primary vehicle image updated.');
    }

    public function deleteImage(int $imageId): void
    {
        $image = $this->vehicle->images()->whereKey($imageId)->firstOrFail();
        $wasPrimary = $image->is_primary;

        Storage::disk('public')->delete($image->path);
        $image->delete();

        if ($wasPrimary) {
            $nextImage = $this->vehicle->images()->orderBy('sort_order')->orderBy('id')->first();

            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        $this->vehicle->load(['supplier', 'images']);
        session()->flash('success', 'Vehicle image deleted.');
    }

    public function render()
    {
        return view('livewire.admin.vehicles.show');
    }
}
