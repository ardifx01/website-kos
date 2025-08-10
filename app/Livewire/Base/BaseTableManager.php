<?php

namespace App\Livewire\Base;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Database\Eloquent\Model;

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
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRecords = $this->getRecords()->pluck('id')->toArray();
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
    }

    public function edit($id)
    {
        $record = $this->getModelClass()::findOrFail($id);
        
        $this->recordId = $record->id;
        $this->loadRecordData($record);
        
        $this->modalMode = 'edit';
        $this->modalTitle = $this->getEditTitle($record);
        $this->showModal = true;
    }

    public function view($id)
    {
        $record = $this->getModelClass()::findOrFail($id);
        
        $this->recordId = $record->id;
        $this->loadRecordData($record);
        
        $this->modalMode = 'view';
        $this->modalTitle = $this->getViewTitle($record);
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate($this->getValidationRules());

        try {
            if ($this->modalMode === 'create') {
                $this->store();
            } else {
                $this->update();
            }

            $this->closeModal();
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => $this->modalMode === 'create' ? $this->getCreateSuccessMessage() : $this->getUpdateSuccessMessage()
            ]);
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $record = $this->getModelClass()::findOrFail($id);
            $this->performDelete($record);

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => $this->getDeleteSuccessMessage()
            ]);
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function bulkDelete()
    {
        if (empty($this->selectedRecords)) {
            $this->dispatch('alert', [
                'type' => 'warning',
                'message' => 'Pilih data yang ingin dihapus!'
            ]);
            return;
        }

        try {
            $records = $this->getModelClass()::whereIn('id', $this->selectedRecords)->get();
            
            foreach ($records as $record) {
                $this->performDelete($record);
            }

            $this->selectedRecords = [];
            $this->selectAll = false;

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Data terpilih berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
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

    // Abstract methods yang harus diimplementasi oleh child class
    abstract protected function getModelClass(): string;
    abstract protected function getRecords();
    abstract protected function getValidationRules(): array;
    abstract protected function resetForm(): void;
    abstract protected function loadRecordData($record): void;
    abstract protected function store(): void;
    abstract protected function update(): void;

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