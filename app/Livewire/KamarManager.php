<?php

namespace App\Livewire;

use App\Models\Kamar;
use App\Models\TipeKamar;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class KamarManager extends BaseTableManager
{
    // Properties specific to Kamar model
    public $nomorKamar;
    public $tipe_kamar_id;
    public $status;
    public $statusFilter = '';
    public $tipeKamarFilter = '';

    // Add filters to query string
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'statusFilter' => ['except' => ''],
        'tipeKamarFilter' => ['except' => ''],
    ];

    // Status options
    public $statusOptions = [
        'tersedia' => 'Tersedia',
        'terisi' => 'Terisi',
        'maintenance' => 'Maintenance',
        'renovasi' => 'Renovasi',
    ];

    public function mount()
    {
        parent::mount();
        $this->sortField = 'nomorKamar'; // Override default sort field
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTipeKamarFilter()
    {
        $this->resetPage();
    }

    protected function getModelClass(): string
    {
        return Kamar::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.kamar-manager';
    }

    // Override the primary key methods since Kamar uses 'id'
    protected function getRecordIdField(): string
    {
        return 'id';
    }

    protected function getRecords()
    {
        return Kamar::with(['tipeKamar', 'creator'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomorKamar', 'like', '%' . $this->search . '%')
                      ->orWhereHas('tipeKamar', function ($tipeQuery) {
                          $tipeQuery->where('nama', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->tipeKamarFilter, function ($query) {
                $query->where('tipe_kamar_id', $this->tipeKamarFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    protected function resetForm(): void
    {
        $this->recordId = null;
        $this->nomorKamar = '';
        $this->tipe_kamar_id = '';
        $this->status = 'tersedia'; // Default status
    }

    protected function loadRecordData($record): void
    {
        $this->nomorKamar = $record->nomorKamar;
        $this->tipe_kamar_id = $record->tipe_kamar_id;
        $this->status = $record->status;
    }

    protected function getValidationRules(): array
    {
        return [
            'nomorKamar' => [
                'required',
                'string',
                'max:10',
                Rule::unique('kamar', 'nomorKamar')->ignore($this->recordId, 'id'),
            ],
            'tipe_kamar_id' => 'required|exists:tipe_kamar,id',
            'status' => 'required|in:tersedia,terisi,maintenance,renovasi',
        ];
    }

    protected function store(): ?Model
    {
        $data = [
            'nomorKamar' => $this->nomorKamar,
            'tipe_kamar_id' => $this->tipe_kamar_id,
            'status' => $this->status,
            'created_by' => auth()->id(),
        ];

        $kamar = Kamar::create($data);

        // Return the created record for global logging
        return $kamar;
    }

    protected function update(): ?Model
    {
        $kamar = Kamar::where('id', $this->recordId)->firstOrFail();
        
        $data = [
            'nomorKamar' => $this->nomorKamar,
            'tipe_kamar_id' => $this->tipe_kamar_id,
            'status' => $this->status,
            'updated_by' => auth()->id(),
        ];

        $kamar->update($data);

        // Return the updated record for global logging
        return $kamar;
    }

    /**
     * Override getLogData untuk customize data yang di-log
     */
    protected function getLogData($record): array
    {
        $data = parent::getLogData($record);
        
        // Tambah informasi tipe kamar ke log
        if ($record->tipeKamar) {
            $data['tipe_kamar_nama'] = $record->tipeKamar->nama;
            $data['harga_sewa'] = $record->tipeKamar->hargaSewa;
        }
        
        return $data;
    }

    protected function cannotDelete($record): bool
    {
        // Tidak bisa hapus kamar yang sedang terisi atau dalam maintenance
        return in_array($record->status, ['terisi', 'maintenance']);
    }
    
    protected function getCannotDeleteMessage($record): string
    {
        if ($record->status === 'terisi') {
            return 'Kamar tidak dapat dihapus karena sedang terisi!';
        }
        
        if ($record->status === 'maintenance') {
            return 'Kamar tidak dapat dihapus karena sedang dalam maintenance!';
        }
        
        return parent::getCannotDeleteMessage($record);
    }

    protected function getAdditionalViewData(): array
    {
        return [
            'tipeKamars' => TipeKamar::all(),
            'statusOptions' => $this->statusOptions,
        ];
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah Kamar Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Kamar: ' . $record->nomorKamar;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Kamar: ' . $record->nomorKamar;
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Kamar berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Kamar berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Kamar berhasil dihapus!';
    }

    // Helper method to get status badge class
    public function getStatusBadgeClass($status)
    {
        $classes = [
            'tersedia' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'terisi' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'maintenance' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'renovasi' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        ];

        return $classes[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }

    // Helper method to format currency
    public function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    // Override delete method to handle custom primary key
    public function delete($id)
    {
        try {
            $record = Kamar::where('id', $id)->firstOrFail();
            
            if ($this->cannotDelete($record)) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => $this->getCannotDeleteMessage($record)
                ]);
                return;
            }

            $record->update(['deleted_by' => auth()->id()]);
            $record->delete();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => $this->getDeleteSuccessMessage()
            ]);

            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data!'
            ]);
        }
    }
}