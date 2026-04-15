<?php

namespace App\Livewire\Admin\Reports;

use App\Models\ComplianceRecord;
use App\Models\CustomerBooking;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Services\Compliance\ComplianceCheckService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Reports')]
class Index extends Component
{
    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?int $supplierReportId = null;
    public ?int $staffReportId = null;

    public function mount(): void
    {
        $this->startDate = now()->subDays(30)->toDateString();
        $this->endDate = now()->toDateString();
        $this->supplierReportId = Supplier::query()->orderBy('business_name')->value('supplier_id');
        $this->staffReportId = User::query()->where('role', 'staff')->orderBy('name')->value('id');
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

    public function updatedSupplierReportId($value): void
    {
        $this->supplierReportId = $value === '' || $value === null ? null : (int) $value;
    }

    public function updatedStaffReportId($value): void
    {
        $this->staffReportId = $value === '' || $value === null ? null : (int) $value;
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

    public function exportSupplierFleetOverview()
    {
        $rows = Supplier::query()
            ->withCount(['vehicles', 'drivers'])
            ->orderBy('business_name')
            ->get();

        $filename = 'supplier-fleet-overview-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['supplier', 'vehicles', 'drivers', 'status']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->business_name, $row->vehicles_count, $row->drivers_count, $row->status]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportVehicleDriverAssignments()
    {
        $rows = Vehicle::query()
            ->with(['supplier', 'drivers'])
            ->withCount('drivers')
            ->orderBy('vehicle_make')
            ->orderBy('vehicle_model')
            ->get();

        $filename = 'vehicle-driver-assignments-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['vehicle', 'plate_number', 'supplier', 'drivers', 'driver_count', 'status']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    trim($row->vehicle_make . ' ' . $row->vehicle_model),
                    $row->plate_number,
                    $row->supplier?->business_name,
                    $row->drivers->pluck('driver_name')->join(', '),
                    $row->drivers_count,
                    $row->status,
                ]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportSelectedSupplierCars()
    {
        $supplier = Supplier::query()
            ->withCount(['vehicles', 'drivers'])
            ->with([
                'vehicles' => fn ($query) => $query->with('drivers')->orderBy('vehicle_make')->orderBy('vehicle_model'),
                'drivers' => fn ($query) => $query->with('vehicle')->orderBy('driver_name'),
            ])
            ->findOrFail($this->supplierReportId);

        $filename = 'supplier-cars-' . $supplier->supplier_id . '-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($supplier) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['supplier', $supplier->business_name]);
            fputcsv($handle, ['number_of_cars', $supplier->vehicles_count]);
            fputcsv($handle, ['number_of_drivers', $supplier->drivers_count]);
            fputcsv($handle, ['status', $supplier->status]);
            fputcsv($handle, []);

            fputcsv($handle, ['vehicle_make', 'vehicle_model', 'plate_number', 'vehicle_category', 'vehicle_status', 'assigned_drivers']);
            foreach ($supplier->vehicles as $vehicle) {
                fputcsv($handle, [
                    $vehicle->vehicle_make,
                    $vehicle->vehicle_model,
                    $vehicle->plate_number,
                    $vehicle->vehicle_category,
                    $vehicle->status,
                    $vehicle->drivers->pluck('driver_name')->join(', '),
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['driver_name', 'phone_number', 'license_number', 'driver_status', 'assigned_vehicle']);
            foreach ($supplier->drivers as $driver) {
                fputcsv($handle, [
                    $driver->driver_name,
                    $driver->phone_number,
                    $driver->license_number,
                    $driver->status,
                    $driver->vehicle ? trim($driver->vehicle->vehicle_make . ' ' . $driver->vehicle->vehicle_model . ' (' . $driver->vehicle->plate_number . ')') : '',
                ]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportStaffOverview()
    {
        [$start, $end] = $this->bookingRange();

        $rows = User::query()
            ->where('role', 'staff')
            ->withCount([
                'suppliers',
                'vehicles',
                'drivers',
                'bookings',
                'bookings as confirmed_bookings_count' => fn ($query) => $query
                    ->where('status', 'confirmed')
                    ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end])),
            ])
            ->orderBy('name')
            ->get();

        $filename = 'staff-overview-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['staff_name', 'email', 'suppliers', 'vehicles', 'drivers', 'bookings', 'confirmed_bookings']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->name,
                    $row->email,
                    $row->suppliers_count,
                    $row->vehicles_count,
                    $row->drivers_count,
                    $row->bookings_count,
                    $row->confirmed_bookings_count,
                ]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportSelectedStaffReport()
    {
        [$start, $end] = $this->bookingRange();

        $staff = User::query()
            ->where('role', 'staff')
            ->withCount([
                'suppliers',
                'vehicles',
                'drivers',
                'bookings' => fn ($query) => $query
                    ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end])),
                'bookings as confirmed_bookings_count' => fn ($query) => $query
                    ->where('status', 'confirmed')
                    ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end])),
            ])
            ->with([
                'suppliers' => fn ($query) => $query->orderBy('business_name'),
                'vehicles' => fn ($query) => $query->with('supplier')->orderBy('vehicle_make')->orderBy('vehicle_model'),
                'drivers' => fn ($query) => $query->with(['supplier', 'vehicle'])->orderBy('driver_name'),
                'bookings' => fn ($query) => $query
                    ->with(['vehicle', 'driver'])
                    ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end]))
                    ->latest('booking_id'),
            ])
            ->findOrFail($this->staffReportId);

        $filename = 'staff-report-' . $staff->id . '-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($staff) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['staff_name', $staff->name]);
            fputcsv($handle, ['email', $staff->email]);
            fputcsv($handle, ['suppliers_count', $staff->suppliers_count]);
            fputcsv($handle, ['vehicles_count', $staff->vehicles_count]);
            fputcsv($handle, ['drivers_count', $staff->drivers_count]);
            fputcsv($handle, ['bookings_count', $staff->bookings_count]);
            fputcsv($handle, ['confirmed_bookings_count', $staff->confirmed_bookings_count]);
            fputcsv($handle, []);

            fputcsv($handle, ['suppliers']);
            fputcsv($handle, ['business_name', 'status', 'city']);
            foreach ($staff->suppliers as $supplier) {
                fputcsv($handle, [$supplier->business_name, $supplier->status, $supplier->city]);
            }
            fputcsv($handle, []);

            fputcsv($handle, ['vehicles']);
            fputcsv($handle, ['vehicle', 'plate_number', 'supplier', 'status']);
            foreach ($staff->vehicles as $vehicle) {
                fputcsv($handle, [
                    trim($vehicle->vehicle_make . ' ' . $vehicle->vehicle_model),
                    $vehicle->plate_number,
                    $vehicle->supplier?->business_name,
                    $vehicle->status,
                ]);
            }
            fputcsv($handle, []);

            fputcsv($handle, ['drivers']);
            fputcsv($handle, ['driver_name', 'supplier', 'assigned_vehicle', 'status']);
            foreach ($staff->drivers as $driver) {
                fputcsv($handle, [
                    $driver->driver_name,
                    $driver->supplier?->business_name,
                    $driver->vehicle ? trim($driver->vehicle->vehicle_make . ' ' . $driver->vehicle->vehicle_model . ' (' . $driver->vehicle->plate_number . ')') : '',
                    $driver->status,
                ]);
            }
            fputcsv($handle, []);

            fputcsv($handle, ['bookings']);
            fputcsv($handle, ['customer_name', 'pickup_time', 'status', 'vehicle', 'driver']);
            foreach ($staff->bookings as $booking) {
                fputcsv($handle, [
                    $booking->customer_name,
                    $booking->pickup_time,
                    $booking->status,
                    $booking->vehicle ? trim($booking->vehicle->vehicle_make . ' ' . $booking->vehicle->vehicle_model . ' (' . $booking->vehicle->plate_number . ')') : '',
                    $booking->driver?->driver_name,
                ]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportComplianceSummary()
    {
        $rows = $this->complianceRecords();
        $filename = 'compliance-summary-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['entity_type', 'entity_label', 'compliance_type', 'document_number', 'expiry_date', 'status']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->entity_type,
                    $this->complianceEntityLabel($row),
                    $row->complianceType?->name,
                    $row->document_number,
                    $row->expiry_date?->toDateString(),
                    $row->status,
                ]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportComplianceExceptions()
    {
        $rows = $this->complianceRecords()
            ->whereIn('status', ['expired', 'non_compliant']);
        $filename = 'compliance-exceptions-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['entity_type', 'entity_label', 'compliance_type', 'document_number', 'expiry_date', 'status']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->entity_type,
                    $this->complianceEntityLabel($row),
                    $row->complianceType?->name,
                    $row->document_number,
                    $row->expiry_date?->toDateString(),
                    $row->status,
                ]);
            }
            fclose($handle);
        }, $filename);
    }

    public function exportSupplierComplianceRanking()
    {
        $rows = $this->supplierComplianceRanking();
        $filename = 'supplier-compliance-ranking-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['supplier', 'score', 'records', 'valid', 'expiring', 'expired', 'non_compliant']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['supplier']->business_name,
                    $row['score'],
                    $row['total_records'],
                    $row['valid_count'],
                    $row['expiring_count'],
                    $row['expired_count'],
                    $row['non_compliant_count'],
                ]);
            }
            fclose($handle);
        }, $filename);
    }

    public function render()
    {
        [$start, $end] = $this->bookingRange();
        $complianceRecords = $this->complianceRecords();
        $supplierComplianceRanking = $this->supplierComplianceRanking($complianceRecords);

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

        $supplierFleetOverview = Supplier::query()
            ->withCount(['vehicles', 'drivers'])
            ->orderByDesc('vehicles_count')
            ->orderByDesc('drivers_count')
            ->orderBy('business_name')
            ->get();

        $vehicleDriverAssignments = Vehicle::query()
            ->with(['supplier', 'drivers'])
            ->withCount('drivers')
            ->orderByDesc('drivers_count')
            ->orderBy('vehicle_make')
            ->orderBy('vehicle_model')
            ->get();

        $suppliers = Supplier::query()
            ->orderBy('business_name')
            ->get(['supplier_id', 'business_name']);

        $staffOverview = User::query()
            ->where('role', 'staff')
            ->withCount([
                'suppliers',
                'vehicles',
                'drivers',
                'bookings' => fn ($query) => $query
                    ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end])),
                'bookings as confirmed_bookings_count' => fn ($query) => $query
                    ->where('status', 'confirmed')
                    ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end])),
            ])
            ->orderByDesc('bookings_count')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $staffMembers = User::query()
            ->where('role', 'staff')
            ->orderBy('name')
            ->get(['id', 'name']);

        $selectedSupplierReport = $this->supplierReportId
            ? Supplier::query()
                ->withCount(['vehicles', 'drivers'])
                ->with([
                    'vehicles' => fn ($query) => $query->with('drivers')->orderBy('vehicle_make')->orderBy('vehicle_model'),
                    'drivers' => fn ($query) => $query->with('vehicle')->orderBy('driver_name'),
                ])
                ->find($this->supplierReportId)
            : null;

        $selectedSupplierCompliance = $selectedSupplierReport
            ? $supplierComplianceRanking->firstWhere('supplier.supplier_id', $selectedSupplierReport->supplier_id)
            : null;

        $selectedStaffReport = $this->staffReportId
            ? User::query()
                ->where('role', 'staff')
                ->withCount([
                    'suppliers',
                    'vehicles',
                    'drivers',
                    'bookings' => fn ($query) => $query
                        ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end])),
                    'bookings as confirmed_bookings_count' => fn ($query) => $query
                        ->where('status', 'confirmed')
                        ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end])),
                ])
                ->with([
                    'suppliers' => fn ($query) => $query->orderBy('business_name'),
                    'vehicles' => fn ($query) => $query->with('supplier')->orderBy('vehicle_make')->orderBy('vehicle_model'),
                    'drivers' => fn ($query) => $query->with(['supplier', 'vehicle'])->orderBy('driver_name'),
                    'bookings' => fn ($query) => $query
                        ->with(['vehicle', 'driver'])
                        ->when($start && $end, fn ($inner) => $inner->whereBetween('pickup_time', [$start, $end]))
                        ->latest('booking_id'),
                ])
                ->find($this->staffReportId)
            : null;

        return view('livewire.admin.reports.index', [
            'bookingSourceLabels' => array_keys($bookingSources),
            'bookingSourceValues' => array_values($bookingSources),
            'vehicleCategoryLabels' => array_keys($vehicleCategories),
            'vehicleCategoryValues' => array_values($vehicleCategories),
            'supplierTierLabels' => array_keys($supplierTiers),
            'supplierTierValues' => array_values($supplierTiers),
            'supplierFleetOverview' => $supplierFleetOverview,
            'vehicleDriverAssignments' => $vehicleDriverAssignments,
            'suppliers' => $suppliers,
            'selectedSupplierReport' => $selectedSupplierReport,
            'complianceSummary' => [
                'total' => $complianceRecords->count(),
                'valid' => $complianceRecords->where('status', 'valid')->count(),
                'expiring' => $complianceRecords->where('status', 'expiring')->count(),
                'expired' => $complianceRecords->where('status', 'expired')->count(),
                'non_compliant' => $complianceRecords->where('status', 'non_compliant')->count(),
            ],
            'complianceExceptions' => $complianceRecords
                ->whereIn('status', ['expired', 'non_compliant'])
                ->sortBy([
                    ['status', 'asc'],
                    ['expiry_date', 'asc'],
                ])
                ->values(),
            'supplierComplianceRanking' => $supplierComplianceRanking,
            'selectedSupplierCompliance' => $selectedSupplierCompliance,
            'staffOverview' => $staffOverview,
            'staffMembers' => $staffMembers,
            'selectedStaffReport' => $selectedStaffReport,
        ]);
    }

    private function complianceRecords(): Collection
    {
        $records = ComplianceRecord::query()
            ->with(['complianceType', 'entity'])
            ->get();

        return app(ComplianceCheckService::class)->refreshCollection($records);
    }

    private function supplierComplianceRanking(?Collection $records = null): Collection
    {
        $records ??= $this->complianceRecords();

        return Supplier::query()
            ->with([
                'vehicles:vehicle_id,supplier_id,vehicle_make,vehicle_model,plate_number',
                'drivers:driver_id,supplier_id,driver_name,license_number',
            ])
            ->orderBy('business_name')
            ->get()
            ->map(function (Supplier $supplier) use ($records) {
                $vehicleIds = $supplier->vehicles->pluck('vehicle_id')->all();
                $driverIds = $supplier->drivers->pluck('driver_id')->all();

                $supplierRecords = $records->filter(function (ComplianceRecord $record) use ($supplier, $vehicleIds, $driverIds) {
                    return ($record->entity_type === 'supplier' && (int) $record->entity_id === (int) $supplier->supplier_id)
                        || ($record->entity_type === 'vehicle' && in_array((int) $record->entity_id, $vehicleIds, true))
                        || ($record->entity_type === 'driver' && in_array((int) $record->entity_id, $driverIds, true));
                })->values();

                $total = $supplierRecords->count();
                $valid = $supplierRecords->where('status', 'valid')->count();
                $expiring = $supplierRecords->where('status', 'expiring')->count();
                $expired = $supplierRecords->where('status', 'expired')->count();
                $nonCompliant = $supplierRecords->where('status', 'non_compliant')->count();

                $score = $total > 0
                    ? (int) round((($valid * 100) + ($expiring * 70) + ($expired * 35)) / $total)
                    : 0;

                return [
                    'supplier' => $supplier,
                    'score' => $score,
                    'total_records' => $total,
                    'valid_count' => $valid,
                    'expiring_count' => $expiring,
                    'expired_count' => $expired,
                    'non_compliant_count' => $nonCompliant,
                    'records' => $supplierRecords,
                ];
            })
            ->sortBy([
                ['score', 'desc'],
                ['non_compliant_count', 'asc'],
                ['expired_count', 'asc'],
                ['supplier.business_name', 'asc'],
            ])
            ->values();
    }

    public function complianceEntityLabel(ComplianceRecord $record): string
    {
        return match ($record->entity_type) {
            'vehicle' => trim(($record->entity?->vehicle_make ?? '') . ' ' . ($record->entity?->vehicle_model ?? '')) . ' (' . ($record->entity?->plate_number ?? '—') . ')',
            'driver' => ($record->entity?->driver_name ?? 'Unknown') . ' (' . ($record->entity?->license_number ?? '—') . ')',
            'supplier' => $record->entity?->business_name ?? 'Unknown',
            default => 'Unknown',
        };
    }
}
