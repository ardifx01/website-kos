<?php

namespace App\Livewire\Base;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Database\Eloquent\Model;
use App\Services\ActivityLogger;

abstract class BaseTableManager extends Component
{
    use WithPagination, WithFileUploads;

    // Properties untuk filtering dan searching
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    // Properties untuk modal
    public $showModal = false;
    public $modalMode = 'create'; // create, edit, view
    public $modalTitle = '';
    public $recordId;

    // Properties untuk bulk actions
    public $selectedRecords = [];
    public $selectAll = false;
    public $bulkAction = '';

    // Properties untuk delete confirmation
    public $showDeleteModal = false;
    public $deleteRecordId = null;
    public $deleteTitle = '';
    public $deleteMessage = '';
    public $deleteDetails = '';
    public $isBulkDelete = false;

    // Properties for activity logging
    protected $enableLogging = true;
    protected $logTableName = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'closeModal' => 'closeModal',
    ];

    public function mount()
    {
        $this->resetPage();
        $this->sortField = $this->getDefaultSortField();
        $this->logTableName = $this->getLogTableName();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // PERBAIKAN: Method untuk mendapatkan primary key dari model
    protected function getModelKeyName(): string
    {
        try {
            $model = app($this->getModelClass());
            return $model->getKeyName();
        } catch (\Exception $e) {
            return 'id'; // fallback ke 'id'
        }
    }

    // PERBAIKAN: Update method updatedSelectAll
    public function updatedSelectAll($value)
    {
        if ($value) {
            $keyName = $this->getModelKeyName();
            $this->selectedRecords = $this->getRecords()->pluck($keyName)->toArray();
        } else {
            $this->selectedRecords = [];
        }
    }

    public function updatedSelectedRecords()
    {
        if (count($this->selectedRecords) === $this->getRecords()->count()) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->modalTitle = $this->getCreateTitle();
        $this->showModal = true;

        // Log activity
        $this->logActivity('form_opened', null, ['action' => 'create']);
    }

    // PERBAIKAN: Method edit dengan primary key yang fleksibel
    public function edit($id)
    {
        $keyName = $this->getModelKeyName();
        $record = $this->getModelClass()::where($keyName, $id)->firstOrFail();
        
        $this->recordId = $record->{$keyName};
        $this->loadRecordData($record);
        
        $this->modalMode = 'edit';
        $this->modalTitle = $this->getEditTitle($record);
        $this->showModal = true;

        // Log activity
        $this->logActivity('form_opened', $record->{$keyName}, ['action' => 'edit']);
    }

    // PERBAIKAN: Method view dengan primary key yang fleksibel
    public function view($id)
    {
        $keyName = $this->getModelKeyName();
        $record = $this->getModelClass()::where($keyName, $id)->firstOrFail();
        
        $this->recordId = $record->{$keyName};
        $this->loadRecordData($record);
        
        $this->modalMode = 'view';
        $this->modalTitle = $this->getViewTitle($record);
        $this->showModal = true;

        // Log activity
        $this->logActivity('viewed', $record->{$keyName}, ['record_name' => $this->getRecordIdentifier($record)]);
    }

    public function save()
    {
        $this->validate($this->getValidationRules());

        try {
            if ($this->modalMode === 'create') {
                $record = $this->store();
                
                if ($record && $this->enableLogging) {
                    $keyName = $this->getModelKeyName();
                    $this->logActivity('created', $record->{$keyName}, $this->getLogData($record), 'Record created via form');
                }
                
                $message = $this->getCreateSuccessMessage();
            } else {
                $keyName = $this->getModelKeyName();
                $oldRecord = $this->getModelClass()::where($keyName, $this->recordId)->first();
                $oldData = $oldRecord ? $this->getLogData($oldRecord) : [];
                
                $record = $this->update();
                
                if ($record && $this->enableLogging) {
                    $newData = $this->getLogData($record->fresh());
                    $this->logActivity('updated', $record->{$keyName}, $this->getChangesData($oldData, $newData), 'Record updated via form');
                }
                
                $message = $this->getUpdateSuccessMessage();
            }

            $this->closeModal();
            
            // Success message
            session()->flash('alert', [
                'type' => 'success',
                'message' => $message
            ]);
            
            // Dispatch event untuk JavaScript
            $this->dispatch('showAlert', [
                'type' => 'success', 
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            // Error message
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
            
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // PERBAIKAN: Method confirmDelete dengan primary key yang fleksibel
    public function confirmDelete($id)
    {
        try {
            $keyName = $this->getModelKeyName();
            $record = $this->getModelClass()::where($keyName, $id)->firstOrFail();
            
            if ($this->cannotDelete($record)) {
                session()->flash('alert', [
                    'type' => 'warning',
                    'message' => $this->getCannotDeleteMessage($record)
                ]);
                
                $this->dispatch('showAlert', [
                    'type' => 'warning',
                    'message' => $this->getCannotDeleteMessage($record)
                ]);
                return;
            }

            $this->deleteRecordId = $id;
            $this->isBulkDelete = false;
            $this->deleteTitle = $this->getDeleteTitle($record) ?: 'Konfirmasi Hapus Data';
            $this->deleteMessage = $this->getDeleteMessage($record) ?: 'Apakah Anda yakin ingin menghapus data ini?';
            $this->deleteDetails = $this->getDeleteDetails($record) ?: $this->getRecordIdentifier($record);
            $this->showDeleteModal = true;

            $this->logActivity('delete_requested', $record->{$keyName}, ['record_name' => $this->getRecordIdentifier($record)]);

        } catch (\Exception $e) {
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
            
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // PERBAIKAN: Method confirmBulkDelete dengan primary key yang fleksibel
    public function confirmBulkDelete()
    {
        try {
            if (empty($this->selectedRecords)) {
                session()->flash('alert', [
                    'type' => 'warning',
                    'message' => 'Pilih data yang ingin dihapus!'
                ]);
                
                $this->dispatch('showAlert', [
                    'type' => 'warning',
                    'message' => 'Pilih data yang ingin dihapus!'
                ]);
                return;
            }

            $keyName = $this->getModelKeyName();
            $records = $this->getModelClass()::whereIn($keyName, $this->selectedRecords)->get();
            $cannotDeleteRecords = [];
            
            foreach ($records as $record) {
                if ($this->cannotDelete($record)) {
                    $cannotDeleteRecords[] = $this->getRecordIdentifier($record);
                }
            }
            
            if (!empty($cannotDeleteRecords)) {
                $message = 'Beberapa data tidak dapat dihapus: ' . implode(', ', $cannotDeleteRecords);
                
                session()->flash('alert', [
                    'type' => 'warning',
                    'message' => $message
                ]);
                
                $this->dispatch('showAlert', [
                    'type' => 'warning',
                    'message' => $message
                ]);
                return;
            }

            $this->isBulkDelete = true;
            $this->deleteTitle = 'Konfirmasi Hapus Massal';
            $this->deleteMessage = 'Apakah Anda yakin ingin menghapus ' . count($this->selectedRecords) . ' data yang dipilih? Tindakan ini tidak dapat dibatalkan.';
            $this->deleteDetails = count($this->selectedRecords) . ' data akan dihapus secara permanen.';
            $this->showDeleteModal = true;

            $this->logActivity('bulk_delete_requested', null, [
                'selected_count' => count($this->selectedRecords),
                'selected_ids' => $this->selectedRecords
            ]);

        } catch (\Exception $e) {
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
            
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function executeDelete()
    {
        try {
            if ($this->isBulkDelete) {
                $this->performBulkDelete();
                $message = $this->getBulkDeleteSuccessMessage();
            } else {
                $this->performSingleDelete();
                $message = $this->getDeleteSuccessMessage();
            }
            
            $this->cancelDelete();
            
            // Success message
            session()->flash('alert', [
                'type' => 'success',
                'message' => $message
            ]);
            
            $this->dispatch('showAlert', [
                'type' => 'success',
                'message' => $message
            ]);

            $this->resetPage();
            
        } catch (\Exception $e) {
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
            
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    protected function showMessage($type, $message)
    {
        session()->flash('alert', [
            'type' => $type,
            'message' => $message
        ]);
        
        $this->dispatch('showAlert', [
            'type' => $type,
            'message' => $message
        ]);
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteRecordId = null;
        $this->deleteTitle = '';
        $this->deleteMessage = '';
        $this->deleteDetails = '';
        $this->isBulkDelete = false;
    }

    // PERBAIKAN: Method performSingleDelete dengan primary key yang fleksibel
    protected function performSingleDelete()
    {
        $keyName = $this->getModelKeyName();
        $record = $this->getModelClass()::where($keyName, $this->deleteRecordId)->firstOrFail();
        
        if ($this->cannotDelete($record)) {
            throw new \Exception($this->getCannotDeleteMessage($record));
        }
        
        // Log before delete
        $recordData = $this->getLogData($record);
        
        $this->performDelete($record);

        // Log delete activity
        $this->logActivity('deleted', $this->deleteRecordId, $recordData, 'Record deleted via form');
    }

    // PERBAIKAN: Method performBulkDelete dengan primary key yang fleksibel
    protected function performBulkDelete()
    {
        $keyName = $this->getModelKeyName();
        $records = $this->getModelClass()::whereIn($keyName, $this->selectedRecords)->get();
        $deletedIds = [];
        $deletedNames = [];
        
        foreach ($records as $record) {
            if ($this->cannotDelete($record)) {
                continue; // Skip records that cannot be deleted
            }
            
            $deletedIds[] = $record->{$keyName};
            $deletedNames[] = $this->getRecordIdentifier($record);
            
            $this->performDelete($record);
        }

        // Log bulk delete activity
        $this->logActivity('bulk_deleted', null, [
            'deleted_count' => count($deletedIds),
            'deleted_ids' => $deletedIds,
            'deleted_names' => $deletedNames
        ], 'Bulk delete performed via form');

        $this->selectedRecords = [];
        $this->selectAll = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    protected function performDelete($record)
    {
        // Check if model uses SoftDeletes
        if (method_exists($record, 'deleted_by')) {
            $record->update(['deleted_by' => auth()->id()]);
        }
        
        $record->delete();
    }

    /**
     * Log activity using ActivityLogger service
     */
    protected function logActivity($event, $recordId = null, $changes = [], $description = null)
    {
        if (!$this->enableLogging) {
            return;
        }

        try {
            ActivityLogger::logCustom(
                $event,
                $this->logTableName,
                $recordId,
                $changes,
                $description
            );
        } catch (\Exception $e) {
            // Silent fail untuk logging agar tidak mengganggu flow utama
            \Log::error('Failed to log activity in BaseTableManager: ' . $e->getMessage());
        }
    }

    /**
     * Get table name for logging
     */
    protected function getLogTableName(): string
    {
        if ($this->logTableName) {
            return $this->logTableName;
        }

        // Try to get table name from model
        try {
            $model = app($this->getModelClass());
            return $model->getTable();
        } catch (\Exception $e) {
            return 'unknown_table';
        }
    }

    /**
     * Get log data from record (override to customize what gets logged)
     */
    protected function getLogData($record): array
    {
        if (!$record) {
            return [];
        }

        $data = $record->toArray();
        
        // Remove sensitive data
        $sensitiveFields = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];
        foreach ($sensitiveFields as $field) {
            unset($data[$field]);
        }
        
        return $data;
    }

    /**
     * Get changes data for logging updates
     */
    protected function getChangesData(array $oldData, array $newData): array
    {
        $changes = [];
        
        foreach ($newData as $key => $value) {
            if (isset($oldData[$key]) && $oldData[$key] != $value) {
                $changes[$key] = [
                    'old' => $oldData[$key],
                    'new' => $value
                ];
            }
        }
        
        return $changes;
    }

    /**
     * Enable or disable logging for this manager
     */
    protected function setLoggingEnabled(bool $enabled)
    {
        $this->enableLogging = $enabled;
    }

    /**
     * Set custom table name for logging
     */
    protected function setLogTableName(string $tableName)
    {
        $this->logTableName = $tableName;
    }

    /**
     * Check if record cannot be deleted
     * Override this method to add custom delete restrictions
     */
    protected function cannotDelete($record): bool
    {
        // Default implementation: prevent user from deleting themselves
        $keyName = $this->getModelKeyName();
        if (property_exists($record, $keyName) && 
            auth()->check() && 
            $record->{$keyName} == auth()->id()) {
            return true;
        }
        
        return false;
    }

    /**
     * Get message when record cannot be deleted
     */
    protected function getCannotDeleteMessage($record): string
    {
        $keyName = $this->getModelKeyName();
        if (property_exists($record, $keyName) && 
            auth()->check() && 
            $record->{$keyName} == auth()->id()) {
            return 'Anda tidak dapat menghapus akun Anda sendiri!';
        }
        
        return 'Data ini tidak dapat dihapus!';
    }

    /**
     * Get record identifier for display purposes
     */
    protected function getRecordIdentifier($record): string
    {
        // Try common identifier fields
        if (property_exists($record, 'name')) {
            return $record->name;
        }
        if (property_exists($record, 'title')) {
            return $record->title;
        }
        if (property_exists($record, 'email')) {
            return $record->email;
        }
        
        $keyName = $this->getModelKeyName();
        return '#' . $record->{$keyName};
    }

    /**
     * Get delete confirmation title
     */
    protected function getDeleteTitle($record): string
    {
        return 'Konfirmasi Hapus Data';
    }

    /**
     * Get delete confirmation message
     */
    protected function getDeleteMessage($record): string
    {
        return 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
    }

    /**
     * Get delete details for confirmation modal
     */
    protected function getDeleteDetails($record): string
    {
        return $this->getRecordIdentifier($record);
    }

    // Legacy method for backward compatibility
    public function delete($id)
    {
        $this->confirmDelete($id);
    }

    // Legacy method for backward compatibility
    public function bulkDelete()
    {
        $this->confirmBulkDelete();
    }

    // Abstract methods yang harus diimplementasi oleh child class
    abstract protected function getModelClass(): string;
    abstract protected function getRecords();
    abstract protected function getValidationRules(): array;
    abstract protected function resetForm(): void;
    abstract protected function loadRecordData($record): void;
    abstract protected function store(): ?Model;
    abstract protected function update(): ?Model;

    // Methods dengan default implementation yang bisa di-override
    protected function getDefaultSortField(): string
    {
        return 'created_at';
    }

    protected function getCreateTitle(): string
    {
        return 'Tambah Data Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Data';
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Data';
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Data berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Data berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Data berhasil dihapus!';
    }

    protected function getBulkDeleteSuccessMessage(): string
    {
        return 'Data terpilih berhasil dihapus!';
    }

    // Method untuk mendapatkan additional data untuk view
    protected function getAdditionalViewData(): array
    {
        return [];
    }

    public function render()
    {
        $viewData = array_merge([
            'records' => $this->getRecords(),
        ], $this->getAdditionalViewData());

        return view($this->getViewName(), $viewData);
    }

    abstract protected function getViewName(): string;
}