<?php

namespace App\Livewire\Admin\Compliance;

use App\Models\ComplianceRecord;
use App\Models\Driver;
use App\Models\Supplier;
use App\Models\Vehicle;
use App\Services\Compliance\ComplianceCheckService;
use App\Support\Compliance\ComplianceEntityMap;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Compliance')]
class Index extends Component
{
    public string $entityType = '';
    public string $status = '';
    public string $search = '';
    public bool $expiringSoonOnly = false;

    public function updatedEntityType($value): void
    {
        $this->entityType = (string) $value;
    }

    public function updatedStatus($value): void
    {
        $this->status = (string) $value;
    }

    public function updatedSearch($value): void
    {
        $this->search = trim((string) $value);
    }

    public function resetFilters(): void
    {
        $this->reset(['entityType', 'status', 'search', 'expiringSoonOnly']);
    }

    public function exportSummary()
    {
        $rows = $this->filteredRecords()->get();

        $filename = 'compliance-summary-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['entity_type', 'entity_name', 'type', 'document_number', 'expiry_date', 'status']);

            foreach ($rows as $record) {
                fputcsv($handle, [
                    $record->entity_type,
                    $this->entityLabel($record),
                    $record->complianceType?->name,
                    $record->document_number,
                    optional($record->expiry_date)->toDateString(),
                    $record->status,
                ]);
            }

            fclose($handle);
        }, $filename);
    }

    public function exportExceptions()
    {
        $rows = $this->filteredRecords()
            ->whereIn('status', ['expired', 'non_compliant'])
            ->get();

        $filename = 'compliance-exceptions-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['entity_type', 'entity_name', 'type', 'document_number', 'expiry_date', 'status']);

            foreach ($rows as $record) {
                fputcsv($handle, [
                    $record->entity_type,
                    $this->entityLabel($record),
                    $record->complianceType?->name,
                    $record->document_number,
                    optional($record->expiry_date)->toDateString(),
                    $record->status,
                ]);
            }

            fclose($handle);
        }, $filename);
    }

    public function render()
    {
        $records = app(ComplianceCheckService::class)->refreshCollection(
            $this->filteredRecords()->limit(50)->get()
        );

        $baseQuery = $this->visibleRecordsQuery();
        $totalRecords = (clone $baseQuery)->count();
        $validCount = (clone $baseQuery)->where('status', 'valid')->count();
        $expiringCount = (clone $baseQuery)->where('status', 'expiring')->count();
        $expiredCount = (clone $baseQuery)->where('status', 'expired')->count();
        $nonCompliantCount = (clone $baseQuery)->where('status', 'non_compliant')->count();

        return view('livewire.admin.compliance.index', [
            'records' => $records,
            'totalRecords' => $totalRecords,
            'validCount' => $validCount,
            'expiringCount' => $expiringCount,
            'expiredCount' => $expiredCount,
            'nonCompliantCount' => $nonCompliantCount,
            'compliantPercentage' => $totalRecords > 0 ? (int) round(($validCount / $totalRecords) * 100) : 0,
            'entityOptions' => array_keys(ComplianceEntityMap::MAP),
        ]);
    }

    private function filteredRecords()
    {
        return $this->visibleRecordsQuery()
            ->when($this->entityType !== '', fn ($query) => $query->where('entity_type', $this->entityType))
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->when($this->expiringSoonOnly, fn ($query) => $query->whereIn('status', ['expiring', 'expired', 'non_compliant']))
            ->when($this->search !== '', function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($inner) use ($search) {
                    $inner->where('document_number', 'like', $search)
                        ->orWhereHas('complianceType', fn ($typeQuery) => $typeQuery->where('name', 'like', $search))
                        ->orWhereHasMorph(
                            'entity',
                            [Vehicle::class, Driver::class, Supplier::class],
                            function ($entityQuery, $type) use ($search) {
                                match ($type) {
                                    Vehicle::class => $entityQuery
                                        ->where('plate_number', 'like', $search)
                                        ->orWhere('vehicle_make', 'like', $search)
                                        ->orWhere('vehicle_model', 'like', $search),
                                    Driver::class => $entityQuery
                                        ->where('driver_name', 'like', $search)
                                        ->orWhere('license_number', 'like', $search),
                                    Supplier::class => $entityQuery->where('business_name', 'like', $search),
                                };
                            }
                        );
                });
            })
            ->latest();
    }

    private function visibleRecordsQuery()
    {
        $user = auth()->user();

        return ComplianceRecord::query()
            ->with(['complianceType', 'entity'])
            ->whereHasMorph(
                'entity',
                [Vehicle::class, Driver::class, Supplier::class],
                fn ($query) => $query->visibleTo($user)
            );
    }

    public function entityLabel(ComplianceRecord $record): string
    {
        return match ($record->entity_type) {
            'vehicle' => trim(($record->entity?->vehicle_make ?? '') . ' ' . ($record->entity?->vehicle_model ?? '')) . ' (' . ($record->entity?->plate_number ?? '—') . ')',
            'driver' => ($record->entity?->driver_name ?? 'Unknown') . ' (' . ($record->entity?->license_number ?? '—') . ')',
            'supplier' => $record->entity?->business_name ?? 'Unknown',
            default => 'Unknown',
        };
    }

    public function entityUrl(ComplianceRecord $record): string
    {
        return match ($record->entity_type) {
            'vehicle' => route('admin.vehicles.show', $record->entity),
            'driver' => route('admin.drivers.show', $record->entity),
            'supplier' => route('admin.suppliers.show', $record->entity),
            default => '#',
        };
    }
}
