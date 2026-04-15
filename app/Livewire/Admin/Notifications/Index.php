<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Notifications')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.notifications.index');
    }
}
