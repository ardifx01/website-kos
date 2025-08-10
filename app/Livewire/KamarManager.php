<?php

namespace App\Livewire;

use App\Models\Kamar;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class KamarManager extends BaseTableManager
{
    // Properties specific to Kamar model
    public $nomorKamar;
    public $tipeKamar;
    public $hargaSewa;
    public $status;
    public $statusFilter = '';
    public $tipeFilter = '';

    // Add filters to query string
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'statusFilter' => ['except' => ''],
        'tipeFilter' => ['except' => ''],
    ];

    // Status options
    public $statusOptions = [
        'tersedia' => 'Tersedia',
        'disewa' => 'Disewa',
        'maintenance' => 'Maintenance',
        'tidak_aktif' => 'Tidak Aktif'
    ];

    // Tipe kamar options
    public $tipeOptions = [
        'single' => 'Single',
        'double' => 'Double',
        'deluxe' => 'Deluxe',
        'suite' => 'Suite'
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

    public function updatingTipeFilter()
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

    protected function getRecords()
    {
        return Kamar::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nomorKamar', 'like', '%' . $this->search . '%')
                      ->orWhere('tipeKamar', 'like', '%' . $this->search . '%')
                      ->orWhere('hargaSewa', 'like', '%' . $this->search . '%')
                      ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->tipeFilter, function ($query) {
                $query->where('tipeKamar', $this->tipeFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    protected function resetForm(): void
    {
        $this->recordId = null;
        $this->nomorKamar = '';
        $this->tipeKamar = '';
        $this->hargaSewa = '';
        $this->status = 'tersedia';
    }

    protected function loadRecordData($record): void
    {
        $this->nomorKamar = $record->nomorKamar;
        $this->tipeKamar = $record->tipeKamar;
        $this->hargaSewa = $record->hargaSewa;
        $this->status = $record->status;
    }

    protected function getValidationRules(): array
    {
        return [
            'nomorKamar' => [
                'required',
                'string',
                'max:20',
                Rule::unique('kamar', 'nomorKamar')->ignore($this->recordId, 'idKamar'),
            ],
            'tipeKamar' => 'required|in:' . implode(',', array_keys($this->tipeOptions)),
            'hargaSewa' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys($this->statusOptions)),
        ];
    }

    protected function store(): ?Model
    {
        $data = [
            'nomorKamar' => $this->nomorKamar,
            'tipeKamar' => $this->tipeKamar,
            'hargaSewa' => $this->hargaSewa,
            'status' => $this->status,
            'created_by' => auth()->id(),
        ];

        $kamar = Kamar::create($data);

        // Return the created record for global logging
        return $kamar;
    }

    protected function update(): ?Model
    {
        $kamar = Kamar::findOrFail($this->recordId);
        
        $data = [
            'nomorKamar' => $this->nomorKamar,
            'tipeKamar' => $this->tipeKamar,
            'hargaSewa' => $this->hargaSewa,
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
        
        // Tambah informasi tambahan ke log
        $data['status_label'] = $this->statusOptions[$record->status] ?? $record->status;
        $data['tipe_label'] = $this->tipeOptions[$record->tipeKamar] ?? $record->tipeKamar;
        $data['harga_formatted'] = 'Rp ' . number_format($record->hargaSewa, 0, ',', '.');
        
        return $data;
    }

    protected function cannotDelete($record): bool
    {
        // Tidak bisa hapus kamar yang sedang disewa
        if ($record->status === 'disewa') {
            return true;
        }
        
        return false;
    }
    
    protected function getCannotDeleteMessage($record): string
    {
        if ($record->status === 'disewa') {
            return 'Kamar yang sedang disewa tidak dapat dihapus!';
        }
        
        return parent::getCannotDeleteMessage($record);
    }

    protected function getAdditionalViewData(): array
    {
        return [
            'statusOptions' => $this->statusOptions,
            'tipeOptions' => $this->tipeOptions,
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

    // Helper method untuk format harga
    public function formatHarga($harga)
    {
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }

    // Helper method untuk badge status
    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'tersedia' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'disewa' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'maintenance' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'tidak_aktif' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    // Helper method untuk badge tipe
    public function getTipeBadgeClass($tipe)
    {
        return match($tipe) {
            'single' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'double' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            'deluxe' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
            'suite' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }
}