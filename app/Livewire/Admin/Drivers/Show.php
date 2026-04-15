<?php

namespace App\Livewire\Admin\Drivers;

use App\Models\Driver;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Driver Details')]
class Show extends Component
{
    public Driver $driver;

    public function mount(Driver $driver): void
    {
        abort_unless($driver->isVisibleTo(auth()->user()), 403);

        $this->driver = $driver->load(['supplier', 'vehicle', 'complianceRecords.complianceType']);
    }

    public function render()
    {
        return view('livewire.admin.drivers.show');
    }
}
