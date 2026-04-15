<?php

namespace App\Services\Compliance;

use App\Models\ComplianceRecord;
use Illuminate\Support\Collection;

class ComplianceCheckService
{
    public function run(): array
    {
        $updated = 0;
        $notifications = 0;

        ComplianceRecord::query()
            ->with(['complianceType', 'creator', 'entity'])
            ->chunkById(100, function ($records) use (&$updated, &$notifications): void {
                foreach ($records as $record) {
                    $original = $record->status;
                    $record->refreshStatus();

                    if ($record->status !== $original) {
                        $updated++;
                        app(ComplianceAuditService::class)->logStatusChange(
                            $record,
                            $original,
                            $record->status,
                            null,
                            ['source' => 'compliance_check']
                        );
                    }

                    $notifications += app(ComplianceNotificationService::class)->sendForRecord($record->loadMissing(['complianceType', 'creator', 'entity']));
                }
            });

        return [
            'updated' => $updated,
            'notifications' => $notifications,
        ];
    }

    public function refreshCollection(Collection $records): Collection
    {
        $records->each->refreshStatus();

        return $records;
    }
}
