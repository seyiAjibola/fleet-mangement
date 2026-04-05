<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\CustomerBooking;
use App\Models\Driver;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Dashboard')]
class Index extends Component
{
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(): void
    {
        $this->startDate = now()->subDays(30)->toDateString();
        $this->endDate = now()->toDateString();
    }

    public function updatedStartDate(): void
    {
        if ($this->startDate === '') {
            $this->startDate = null;
        }

        $this->dispatch('zeno-charts-refresh');
    }

    public function updatedEndDate(): void
    {
        if ($this->endDate === '') {
            $this->endDate = null;
        }

        $this->dispatch('zeno-charts-refresh');
    }

    public function resetFilters(): void
    {
        $this->startDate = now()->subDays(30)->toDateString();
        $this->endDate = now()->toDateString();
        $this->dispatch('zeno-charts-refresh');
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

    public function exportBookingStatus()
    {
        [$start, $end] = $this->bookingRange();

        $rows = CustomerBooking::query()
            ->visibleTo(auth()->user())
            ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $filename = 'booking-status-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['status', 'total']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->status, $row->total]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportVehicleStatus()
    {
        $rows = Vehicle::query()
            ->visibleTo(auth()->user())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $filename = 'vehicle-status-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['status', 'total']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->status, $row->total]);
            }
            fclose($handle);
        }, $filename);
    }

    public function render()
    {
        $user = auth()->user();
        [$start, $end] = $this->bookingRange();

        $bookingStatusCounts = CustomerBooking::query()
            ->visibleTo($user)
            ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $vehicleStatusCounts = Vehicle::query()
            ->visibleTo($user)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return view('livewire.admin.dashboard.index', [
            'isAdmin' => $user->isAdmin(),
            'userCount' => $user->isAdmin() ? User::query()->count() : null,
            'supplierCount' => Supplier::query()->visibleTo($user)->count(),
            'vehicleCount' => Vehicle::query()->visibleTo($user)->count(),
            'driverCount' => Driver::query()->visibleTo($user)->count(),
            'bookingCount' => CustomerBooking::query()
                ->visibleTo($user)
                ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
                ->count(),
            'confirmedBookings' => CustomerBooking::query()
                ->visibleTo($user)
                ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
                ->where('status', 'confirmed')
                ->count(),
            'pendingBookings' => CustomerBooking::query()
                ->visibleTo($user)
                ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
                ->where('status', 'pending')
                ->count(),
            'rejectedBookings' => CustomerBooking::query()
                ->visibleTo($user)
                ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
                ->where('status', 'rejected')
                ->count(),
            'canceledBookings' => CustomerBooking::query()
                ->visibleTo($user)
                ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
                ->where('status', 'cancelled')
                ->count(),
            'availableVehicles' => Vehicle::query()->visibleTo($user)->where('status', 'available')->count(),
            'bookingStatusLabels' => array_keys($bookingStatusCounts),
            'bookingStatusValues' => array_values($bookingStatusCounts),
            'vehicleStatusLabels' => array_keys($vehicleStatusCounts),
            'vehicleStatusValues' => array_values($vehicleStatusCounts),
        ]);
    }
}
