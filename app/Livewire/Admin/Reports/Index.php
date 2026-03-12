<?php

namespace App\Livewire\Admin\Reports;

use App\Models\CustomerBooking;
use App\Models\Supplier;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Reports')]
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

    public function exportBookingSources()
    {
        [$start, $end] = $this->bookingRange();

        $rows = CustomerBooking::query()
            ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
            ->selectRaw('booking_source, count(*) as total')
            ->groupBy('booking_source')
            ->orderBy('booking_source')
            ->get();

        $filename = 'booking-sources-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['booking_source', 'total']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->booking_source, $row->total]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportVehicleCategories()
    {
        $rows = Vehicle::query()
            ->selectRaw('vehicle_category, count(*) as total')
            ->groupBy('vehicle_category')
            ->orderBy('vehicle_category')
            ->get();

        $filename = 'vehicle-categories-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['vehicle_category', 'total']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->vehicle_category, $row->total]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportSupplierTiers()
    {
        $rows = Supplier::query()
            ->selectRaw('supplier_tier, count(*) as total')
            ->groupBy('supplier_tier')
            ->orderBy('supplier_tier')
            ->get();

        $filename = 'supplier-tiers-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['supplier_tier', 'total']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->supplier_tier, $row->total]);
            }
            fclose($handle);
        }, $filename);
    }

    public function render()
    {
        [$start, $end] = $this->bookingRange();

        $bookingSources = CustomerBooking::query()
            ->when($start && $end, fn ($query) => $query->whereBetween('pickup_time', [$start, $end]))
            ->selectRaw('booking_source, count(*) as total')
            ->groupBy('booking_source')
            ->pluck('total', 'booking_source')
            ->all();

        $vehicleCategories = Vehicle::query()
            ->selectRaw('vehicle_category, count(*) as total')
            ->groupBy('vehicle_category')
            ->pluck('total', 'vehicle_category')
            ->all();

        $supplierTiers = Supplier::query()
            ->selectRaw('supplier_tier, count(*) as total')
            ->groupBy('supplier_tier')
            ->pluck('total', 'supplier_tier')
            ->all();

        return view('livewire.admin.reports.index', [
            'bookingSourceLabels' => array_keys($bookingSources),
            'bookingSourceValues' => array_values($bookingSources),
            'vehicleCategoryLabels' => array_keys($vehicleCategories),
            'vehicleCategoryValues' => array_values($vehicleCategories),
            'supplierTierLabels' => array_keys($supplierTiers),
            'supplierTierValues' => array_values($supplierTiers),
        ]);
    }
}
