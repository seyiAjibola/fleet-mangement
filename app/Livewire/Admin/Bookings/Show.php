<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\CustomerBooking;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Booking Details')]
class Show extends Component
{
    public CustomerBooking $booking;

    public function mount(CustomerBooking $booking): void
    {
        abort_unless($booking->isVisibleTo(auth()->user()), 403);

        $this->booking = $booking->load(['vehicle', 'driver']);
    }

    public function render()
    {
        return view('livewire.admin.bookings.show');
    }
}
