<?php

namespace App\Services\Compliance;

use App\Models\ComplianceAuditLog;
use App\Models\ComplianceDocument;
use App\Models\ComplianceRecord;
use App\Models\User;

class ComplianceAuditService
{
    public function logCreate(ComplianceRecord $record, ?User $actor = null): void
    {
        $this->log($record, $actor, 'created', 'Compliance record created.', [], $this->recordSnapshot($record));
    }

    public function logUpdate(ComplianceRecord $record, array $oldValues, ?User $actor = null): void
    {
        $this->log(
            $record,
            $actor,
            'updated',
            'Compliance record updated.',
            $oldValues,
            $this->recordSnapshot($record)
        );
    }

    public function logDocumentAdded(ComplianceRecord $record, ComplianceDocument $document, ?User $actor = null): void
    {
        $this->log(
            $record,
            $actor,
            'document_added',
            'Supporting document added.',
            [],
            ['document_id' => $document->id, 'file_type' => $document->file_type, 'file_path' => $document->file_path]
        );
    }

    public function logDocumentRemoved(ComplianceRecord $record, ComplianceDocument $document, ?User $actor = null): void
    {
        $this->log(
            $record,
            $actor,
            'document_removed',
            'Supporting document removed.',
            ['document_id' => $document->id, 'file_type' => $document->file_type, 'file_path' => $document->file_path],
            []
        );
    }

    public function logStatusChange(ComplianceRecord $record, string $oldStatus, string $newStatus, ?User $actor = null, array $meta = []): void
    {
        $this->log(
            $record,
            $actor,
            'status_changed',
            "Compliance status changed from {$oldStatus} to {$newStatus}.",
            ['status' => $oldStatus],
            ['status' => $newStatus],
            $meta
        );
    }

    private function log(
        ComplianceRecord $record,
        ?User $actor,
        string $action,
        string $summary,
        array $oldValues = [],
        array $newValues = [],
        array $meta = []
    ): void {
        ComplianceAuditLog::query()->create([
            'compliance_record_id' => $record->getKey(),
            'actor_id' => $actor?->getKey(),
            'action' => $action,
            'summary' => $summary,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'meta' => $meta,
        ]);
    }

    private function recordSnapshot(ComplianceRecord $record): array
    {
        return [
            'compliance_type_id' => $record->compliance_type_id,
            'document_number' => $record->document_number,
            'issued_date' => $record->issued_date?->toDateString(),
            'expiry_date' => $record->expiry_date?->toDateString(),
            'status' => $record->status,
            'entity_type' => $record->entity_type,
            'entity_id' => $record->entity_id,
        ];
    }
}
