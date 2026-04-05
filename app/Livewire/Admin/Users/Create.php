<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Create User')]
class Create extends Component
{
    public string $name = '';
    public ?string $nin = null;
    public string $role = 'staff';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nin' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:admin,staff'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        User::query()->create([
            'name' => $validated['name'],
            'nin' => $validated['nin'],
            'role' => $validated['role'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $this->redirectRoute('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }
}
