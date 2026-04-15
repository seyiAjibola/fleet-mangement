<?php

namespace App\Livewire\Admin\Compliance;

use App\Models\ComplianceDocument;
use App\Models\ComplianceRecord;
use App\Services\Compliance\ComplianceAuditService;
use App\Services\Compliance\ComplianceService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ComplianceForm extends Component
{
    use WithFileUploads;

    public $recordId;
    public $entityId;
    public $entityType;

    public $compliance_type_id;
    public $document_number;
    public $issued_date;
    public $expiry_date;
    public array $documents = [];

    public $showModal = false;

    protected $listeners = [
        'openComplianceForm' => 'open',
    ];

    public function open($recordId = null, $entityId = null, $entityType = null)
    {
        $this->resetForm();
        $service = app(ComplianceService::class);

        if ($recordId) {
            $record = $service->findAccessibleRecord((int) $recordId, auth()->user());

            $this->recordId = $record->id;
            $this->entityId = $record->entity_id;
            $this->entityType = $record->entity_type;
            $this->compliance_type_id = $record->compliance_type_id;
            $this->document_number = $record->document_number;
            $this->issued_date = $record->issued_date?->format('Y-m-d');
            $this->expiry_date = $record->expiry_date?->format('Y-m-d');
        } else {
            $entity = $service->resolveEntity((string) $entityType, $entityId, auth()->user());
            $this->entityId = $entity->getKey();
            $this->entityType = $entity->getMorphClass();
        }

        $this->showModal = true;
    }

    public function save()
    {
        $service = app(ComplianceService::class);
        $entity = $service->resolveEntity((string) $this->entityType, $this->entityId, auth()->user());

        $data = $this->validate([
            'compliance_type_id' => 'required|exists:compliance_types,id',
            'document_number' => 'nullable|string',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:5120|mimes:pdf,jpg,jpeg,png,webp',
        ]);

        if ($this->recordId) {
            $record = $service->updateRecord(
                ComplianceRecord::query()->findOrFail($this->recordId),
                $data,
                auth()->user()
            );
        } else {
            $record = $service->createRecord($entity, $data, auth()->user());
        }

        $this->storeDocuments($record);
        $record->refreshStatus();
        $this->dispatch('complianceUpdated');
        $this->resetForm();
    }

    public function render()
    {
        $record = $this->recordId
            ? app(ComplianceService::class)->findAccessibleRecord((int) $this->recordId, auth()->user())
            : null;

        return view('livewire.admin.compliance.compliance-form', [
            'types' => $this->entityType
                ? app(ComplianceService::class)->availableTypesFor((string) $this->entityType)
                : collect(),
            'existingDocuments' => $record?->documents()->latest()->get() ?? collect(),
            'auditLogs' => $record?->auditLogs()->with('actor')->get() ?? collect(),
        ]);
    }

    public function removeDocument(int $documentId): void
    {
        $record = app(ComplianceService::class)->findAccessibleRecord((int) $this->recordId, auth()->user());
        $document = $record->documents()->findOrFail($documentId);
        $auditPayload = $document->replicate();

        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        app(ComplianceAuditService::class)->logDocumentRemoved(
            $record,
            $auditPayload->forceFill(['id' => $documentId]),
            auth()->user()
        );

        $this->dispatch('complianceUpdated');
    }

    public function close(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'recordId',
            'entityId',
            'entityType',
            'compliance_type_id',
            'document_number',
            'issued_date',
            'expiry_date',
            'documents',
            'showModal',
        ]);

        $this->resetValidation();
    }

    private function storeDocuments(ComplianceRecord $record): void
    {
        foreach ($this->documents as $document) {
            $path = $document->store(
                'compliance/' . $record->entity_type . '/' . $record->entity_id . '/' . $record->getKey(),
                'public'
            );

            $record->documents()->create([
                'file_path' => $path,
                'file_type' => $document->getClientOriginalExtension(),
                'uploaded_by' => auth()->id(),
            ]);
            app(ComplianceAuditService::class)->logDocumentAdded(
                $record,
                $record->documents()->latest('id')->first(),
                auth()->user()
            );
        }
    }

}
