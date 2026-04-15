<?php

namespace App\Services\Compliance;

use App\Models\ComplianceRecord;
use App\Models\ComplianceType;
use App\Models\User;
use App\Support\Compliance\ComplianceEntityMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ComplianceService
{
    public function availableTypesFor(string $entityType): Collection
    {
        $alias = ComplianceEntityMap::aliasFor($entityType);

        return ComplianceType::query()
            ->active()
            ->forEntity($alias)
            ->orderBy('name')
            ->get();
    }

    public function resolveEntity(string $entityType, int|string $entityId, ?User $actor = null): Model
    {
        $modelClass = ComplianceEntityMap::modelClassFor($entityType);

        return $modelClass::query()
            ->visibleTo($actor)
            ->findOrFail($entityId);
    }

    public function findAccessibleRecord(int $recordId, ?User $actor = null): ComplianceRecord
    {
        $record = ComplianceRecord::query()
            ->with(['entity', 'complianceType', 'auditLogs.actor'])
            ->findOrFail($recordId);

        abort_unless(
            $record->entity
            && method_exists($record->entity, 'isVisibleTo')
            && $record->entity->isVisibleTo($actor),
            403
        );

        return $record;
    }

    public function createRecord(Model $entity, array $attributes, ?User $actor = null): ComplianceRecord
    {
        $type = $this->resolveTypeForEntity($entity, (int) $attributes['compliance_type_id']);

        $record = new ComplianceRecord([
            'document_number' => $attributes['document_number'] ?? null,
            'issued_date' => $attributes['issued_date'] ?? null,
            'expiry_date' => $attributes['expiry_date'] ?? null,
            'last_notified_at' => $attributes['last_notified_at'] ?? null,
            'resolved_at' => $attributes['resolved_at'] ?? null,
        ]);

        $record->complianceType()->associate($type);
        $record->entity()->associate($entity);

        if ($actor) {
            $record->creator()->associate($actor);
        }

        $record->save();
        app(ComplianceAuditService::class)->logCreate($record, $actor);

        return $record->load('complianceType');
    }

    public function updateRecord(ComplianceRecord $record, array $attributes, ?User $actor = null): ComplianceRecord
    {
        $record = $this->findAccessibleRecord($record->getKey(), $actor);
        $type = $this->resolveTypeForEntity($record->entity, (int) $attributes['compliance_type_id']);
        $oldValues = [
            'compliance_type_id' => $record->compliance_type_id,
            'document_number' => $record->document_number,
            'issued_date' => $record->issued_date?->toDateString(),
            'expiry_date' => $record->expiry_date?->toDateString(),
            'status' => $record->status,
        ];

        $record->fill([
            'compliance_type_id' => $type->getKey(),
            'document_number' => $attributes['document_number'] ?? null,
            'issued_date' => $attributes['issued_date'] ?? null,
            'expiry_date' => $attributes['expiry_date'] ?? null,
        ]);

        $record->save();
        app(ComplianceAuditService::class)->logUpdate($record, $oldValues, $actor);

        return $record->load('complianceType');
    }

    private function resolveTypeForEntity(Model $entity, int $complianceTypeId): ComplianceType
    {
        $alias = ComplianceEntityMap::aliasFor($entity);

        return ComplianceType::query()
            ->active()
            ->forEntity($alias)
            ->findOrFail($complianceTypeId);
    }
}
