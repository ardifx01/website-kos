<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UserManager extends BaseTableManager
{
    // Properties specific to User model
    public $role_id;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $photo;
    public $photoPreview;
    public $password;
    public $password_confirmation;
    public $roleFilter = '';

    // Add role filter to query string
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'roleFilter' => ['except' => ''],
    ];

    public function mount()
    {
        parent::mount();
        $this->sortField = 'name'; // Override default sort field
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.user-manager';
    }

    protected function getRecords()
    {
        return User::with('role')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhereHas('role', function ($roleQuery) {
                          $roleQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role_id', $this->roleFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    protected function resetForm(): void
    {
        $this->recordId = null;
        $this->role_id = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->photo = null;
        $this->photoPreview = null;
        $this->password = '';
        $this->password_confirmation = '';
    }

    protected function loadRecordData($record): void
    {
        $this->role_id = $record->role_id;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->phone = $record->phone;
        $this->address = $record->address;
        $this->photoPreview = $record->photo ? Storage::url($record->photo) : null;
    }

    protected function getValidationRules(): array
    {
        $rules = [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->recordId),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ];

        if ($this->modalMode === 'create') {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    protected function store(): ?Model
    {
        $data = [
            'role_id' => $this->role_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'password' => Hash::make($this->password),
            'created_by' => auth()->id(),
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('users', 'public');
        }

        $user = User::create($data);

        // Return the created record for global logging
        return $user;
    }

    protected function update(): ?Model
    {
        $user = User::findOrFail($this->recordId);
        
        $data = [
            'role_id' => $this->role_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'updated_by' => auth()->id(),
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->photo) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $this->photo->store('users', 'public');
        }

        $user->update($data);

        // Return the updated record for global logging
        return $user;
    }

    /**
     * Override getLogData untuk customize data yang di-log
     * Menambahkan informasi role dan menghapus data sensitif
     */
    protected function getLogData($record): array
    {
        $data = parent::getLogData($record);
        
        // Tambah informasi role ke log
        if ($record->role) {
            $data['role_name'] = $record->role->name;
        }
        
        // Hapus data sensitif tambahan yang spesifik untuk User
        unset($data['email_verified_at']);
        
        return $data;
    }

    protected function cannotDelete($record): bool
    {
        // Tidak bisa hapus diri sendiri
        if (auth()->id() == $record->id) {
            return true;
        }
        
        // Tidak bisa hapus super admin
        if ($record->role && $record->role->name === 'super-admin') {
            return true;
        }

        
        return false;
    }
    
    protected function getCannotDeleteMessage($record): string
    {
        if (auth()->id() == $record->id) {
            return 'Anda tidak dapat menghapus akun Anda sendiri!';
        }
        
        if ($record->role && $record->role->name === 'super-admin') {
            return true;
        }
        
        return parent::getCannotDeleteMessage($record);
    }

    protected function getAdditionalViewData(): array
    {
        return [
            'roles' => Role::all(),
        ];
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah User Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit User: ' . $record->name;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail User: ' . $record->name;
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'User berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'User berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'User berhasil dihapus!';
    }
}