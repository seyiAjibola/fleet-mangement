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

    public string $userName = '';
    public string $nin = '';

    protected $queryString = [
        'userName' => ['except' => ''],
        'nin' => ['except' => ''],
    ];

    public function updatedUserName(): void
    {
        $this->resetPage();
    }

    public function updatedNin(): void
    {
        $this->resetPage();
    }

    public function applyFilters(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['userName', 'nin']);
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
                ->when($this->userName !== '', fn ($query) => $query->where('name', 'like', '%' . $this->userName . '%'))
                ->when($this->nin !== '', fn ($query) => $query->where('nin', 'like', '%' . $this->nin . '%'))
                ->latest()
                ->paginate(10),
        ]);
    }
}
