<?php

namespace App\Livewire\Admin\Compliance;

use App\Services\Compliance\ComplianceCheckService;
use Livewire\Component;

class ComplianceList extends Component
{
    public $entity;
    public $records = [];

    protected $listeners = [
        'complianceUpdated' => 'loadRecords',
    ];

    public function mount($entity)
    {
        $this->entity = $entity;
        $this->loadRecords();
    }

    public function loadRecords()
    {
        $records = $this->entity
            ->complianceRecords()
            ->with('complianceType')
            ->latest()
            ->get();

        $this->records = app(ComplianceCheckService::class)->refreshCollection($records);
    }

    public function create()
    {
        $this->dispatch('openComplianceForm', entityId: $this->entity->getKey(), entityType: $this->entity->getMorphClass());
    }

    public function edit($recordId)
    {
        $this->dispatch('openComplianceForm', recordId: $recordId);
    }

    public function render()
    {
        return view('livewire.admin.compliance.compliance-list');
    }
}
