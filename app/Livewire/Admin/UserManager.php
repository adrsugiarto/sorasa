<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Akun Admin & Pengguna — SORASA')]
class UserManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $roleFilter = '';

    // Modal state
    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $userId = null;

    // Form fields
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'admin';

    /**
     * Authorize that only the owner can access this module.
     */
    public function mount(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->isOwner()) {
            abort(403, 'Akses terbatas untuk Pemilik Usaha (Owner).');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditing = false;
        $this->role = 'admin';
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $user = User::query()->findOrFail($id);

        $this->userId = $user->id;
        $this->isEditing = true;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->password_confirmation = '';

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'admin';
    }

    /**
     * Save user (create or update).
     */
    public function save(): void
    {
        if ($this->isEditing) {
            $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$this->userId],
                'role' => ['required', 'in:owner,admin'],
                'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            ]);

            $user = User::query()->findOrFail($this->userId);

            // Prevent owner from demoting themselves if they are the only owner
            if ($user->id === Auth::id() && $this->role !== 'owner') {
                $ownerCount = User::where('role', 'owner')->count();
                if ($ownerCount <= 1) {
                    $this->addError('role', 'Anda tidak dapat mengubah role Anda sendiri karena merupakan satu-satunya Owner.');

                    return;
                }
            }

            $payload = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ];

            if (! empty($this->password)) {
                $payload['password'] = Hash::make($this->password);
            }

            $user->update($payload);
            session()->flash('success', "Akun {$user->name} berhasil diperbarui.");
        } else {
            $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'role' => ['required', 'in:owner,admin'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);

            session()->flash('success', "Akun {$this->name} sebagai {$this->role} berhasil dibuat.");
        }

        $this->closeModal();
    }

    /**
     * Delete an admin or user account.
     */
    public function delete(int $id): void
    {
        if ($id === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');

            return;
        }

        $user = User::query()->findOrFail($id);
        $name = $user->name;

        // If deleting an owner, ensure at least one owner remains
        if ($user->role === 'owner') {
            $ownerCount = User::where('role', 'owner')->count();
            if ($ownerCount <= 1) {
                session()->flash('error', 'Tidak dapat menghapus Owner terakhir.');

                return;
            }
        }

        $user->delete();
        session()->flash('success', "Akun {$name} berhasil dihapus.");
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        $query = User::query()
            ->whereIn('role', ['owner', 'admin'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->roleFilter, function ($q) {
                $q->where('role', $this->roleFilter);
            });

        $users = $query->orderBy('role')->latest('id')->paginate(10);

        $totalOwner = User::where('role', 'owner')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        return view('livewire.admin.user-manager', [
            'users' => $users,
            'totalOwner' => $totalOwner,
            'totalAdmin' => $totalAdmin,
        ]);
    }
}
