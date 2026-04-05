<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\CustomerBooking;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Admin - Bookings')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public ?string $startDate = null;
    public ?string $endDate = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->startDate = null;
        $this->endDate = null;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedStartDate(): void
    {
        if ($this->startDate === '') {
            $this->startDate = null;
        }

        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        if ($this->endDate === '') {
            $this->endDate = null;
        }

        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->resetPage();
    }

    public function applyFilters(): void
    {
        $this->resetPage();
    }

    private function bookingRange(): array
    {
        try {
            $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
            $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;
        } catch (\Throwable $e) {
            return [null, null];
        }

        if ($start && $end && $end->lt($start)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return [$start, $end];
    }

    public function delete(int $id): void
    {
        try {
            CustomerBooking::query()->visibleTo(auth()->user())->whereKey($id)->delete();
            session()->flash('success', 'Booking deleted.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to delete booking.');
        }

        $this->resetPage();
    }

    public function confirmBooking(int $id): void
    {
        $this->updateStatus($id, 'confirmed');
    }

    public function rejectBooking(int $id): void
    {
        $this->updateStatus($id, 'rejected');
    }

    public function cancelBooking(int $id): void
    {
        $this->updateStatus($id, 'cancelled');
    }

    private function updateStatus(int $id, string $status): void
    {
        try {
            CustomerBooking::query()->visibleTo(auth()->user())->whereKey($id)->update(['status' => $status]);
            session()->flash('success', 'Booking status updated.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to update booking status.');
        }

        $this->resetPage();
    }

    public function render()
    {
        [$start, $end] = $this->bookingRange();

        return view('livewire.admin.bookings.index', [
            'bookings' => CustomerBooking::query()
                ->visibleTo(auth()->user())
                ->when($this->search !== '', function ($query) {
                    $query->where(function ($inner) {
                        $inner->where('customer_name', 'like', '%' . $this->search . '%')
                            ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
                            ->orWhere('pickup_location', 'like', '%' . $this->search . '%')
                            ->orWhere('dropoff_location', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->status !== '', function ($query) {
                    $query->where('status', $this->status);
                })
                ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
                ->latest('booking_id')
                ->paginate(10),
        ]);
    }
}
