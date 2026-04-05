<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin - Edit User')]
class Edit extends Component
{
    public User $user;
    public string $name = '';
    public ?string $nin = null;
    public string $role = 'staff';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->nin = $user->nin;
        $this->role = $user->role;
        $this->email = $user->email;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nin' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:admin,staff'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name' => $validated['name'],
            'nin' => $validated['nin'],
            'role' => $validated['role'],
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $this->user->update($data);

        $this->redirectRoute('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.edit');
    }
}
