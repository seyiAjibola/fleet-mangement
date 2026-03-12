<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Admin - Users')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function applyFilters(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        try {
            User::query()->whereKey($id)->delete();
            session()->flash('success', 'User deleted.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Unable to delete user.');
        }

        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.users.index', [
            'users' => User::query()
                ->when($this->search !== '', function ($query) {
                    $query->where(function ($inner) {
                        $inner->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
