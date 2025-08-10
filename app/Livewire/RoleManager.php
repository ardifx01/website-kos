<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class RoleManager extends BaseTableManager
{
    // Properties specific to Role model
    public $name;
    public $description;

    public function mount()
    {
        parent::mount();
        $this->sortField = 'name'; // Override default sort field
    }

    protected function getModelClass(): string
    {
        return Role::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.role-manager';
    }

    protected function getRecords()
    {
        return Role::withCount('users')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    protected function resetForm(): void
    {
        $this->recordId = null;
        $this->name = '';
        $this->description = '';
    }

    protected function loadRecordData($record): void
    {
        $this->name = $record->name;
        $this->description = $record->description;
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->recordId),
            ],
            'description' => 'nullable|string|max:500',
        ];
    }

    protected function store(): ?Model
    {
        $role = Role::create([
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => auth()->id(),
        ]);

        // Return the created record for global logging
        return $role;
    }

    protected function update(): ?Model
    {
        $role = Role::findOrFail($this->recordId);
        
        $role->update([
            'name' => $this->name,
            'description' => $this->description,
            'updated_by' => auth()->id(),
        ]);

        // Return the updated record for global logging
        return $role;
    }

    /**
     * Override getLogData untuk customize data yang di-log
     * Menambahkan informasi jumlah users yang menggunakan role ini
     */
    protected function getLogData($record): array
    {
        $data = parent::getLogData($record);
        
        // Tambah informasi jumlah users yang menggunakan role ini
        if ($record->relationLoaded('users')) {
            $data['users_count'] = $record->users->count();
        } else {
            $data['users_count'] = $record->users()->count();
        }
        
        return $data;
    }

    protected function cannotDelete($record): bool
    {
        // Tidak bisa hapus role super-admin
        if (strtolower($record->name) === 'super-admin' || strtolower($record->name) === 'super admin') {
            return true;
        }
        
        // Tidak bisa hapus role yang masih digunakan oleh user
        if ($record->users()->count() > 0) {
            return true;
        }
        
        return false;
    }
    
    protected function getCannotDeleteMessage($record): string
    {
        if (strtolower($record->name) === 'super-admin' || strtolower($record->name) === 'super admin') {
            return 'Role Super Admin tidak dapat dihapus!';
        }
        
        if ($record->users()->count() > 0) {
            return 'Role ini tidak dapat dihapus karena masih digunakan oleh ' . $record->users()->count() . ' user!';
        }
        
        return parent::getCannotDeleteMessage($record);
    }

    protected function getRecordIdentifier($record): string
    {
        return $record->name;
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah Role Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Role: ' . $record->name;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Role: ' . $record->name;
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Role berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Role berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Role berhasil dihapus!';
    }

    protected function getDeleteTitle($record): string
    {
        return 'Konfirmasi Hapus Role';
    }

    protected function getDeleteMessage($record): string
    {
        return 'Apakah Anda yakin ingin menghapus role "' . $record->name . '"? Tindakan ini tidak dapat dibatalkan.';
    }

    protected function getDeleteDetails($record): string
    {
        $details = 'Role: ' . $record->name;
        
        if ($record->users_count > 0) {
            $details .= ' | Digunakan oleh: ' . $record->users_count . ' user';
        }
        
        if ($record->description) {
            $details .= ' | Deskripsi: ' . $record->description;
        }
        
        return $details;
    }
}