<?php

namespace App\Livewire\Admin\Compliance;

use Livewire\Component;

class ComplianceBadge extends Component
{
    public $status;

    public function render()
    {
        return view('livewire.admin.compliance.compliance-badge');
    }
}
