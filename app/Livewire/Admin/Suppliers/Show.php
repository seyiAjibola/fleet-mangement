<?php

namespace App\Livewire\Admin\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Supplier Details')]
class Show extends Component
{
    public Supplier $supplier;

    public function mount(Supplier $supplier): void
    {
        abort_unless($supplier->isVisibleTo(auth()->user()), 403);

        $user = auth()->user();

        $this->supplier = $supplier->loadCount('vehicles')
            ->load([
                'vehicles' => fn ($query) => $query
                    ->visibleTo($user)
                    ->with(['drivers' => fn ($driverQuery) => $driverQuery->visibleTo($user)->orderBy('driver_name')])
                    ->orderBy('vehicle_make')
                    ->orderBy('vehicle_model'),
                'drivers' => fn ($query) => $query
                    ->visibleTo($user)
                    ->with('vehicle')
                    ->orderBy('driver_name'),
            ]);
    }

    public function render()
    {
        return view('livewire.admin.suppliers.show');
    }
}
