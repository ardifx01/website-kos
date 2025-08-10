<?php

namespace App\Livewire;

use App\Models\FasilitasKamar;
use App\Models\Kamar;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class FasilitasKamarManager extends BaseTableManager
{
    // Properties specific to FasilitasKamar model
    public $idKamar;
    public $namaFasilitas;
    public $kondisi;
    public $kamarFilter = '';
    public $kondisiFilter = '';

    // Add filters to query string
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'kamarFilter' => ['except' => ''],
        'kondisiFilter' => ['except' => ''],
    ];

    // Kondisi options
    public $kondisiOptions = [
        'baik' => 'Baik',
        'rusak_ringan' => 'Rusak Ringan',
        'rusak_berat' => 'Rusak Berat',
        'tidak_berfungsi' => 'Tidak Berfungsi'
    ];

    // Kamar options (will be loaded from database)
    public $kamarOptions = [];

    public function mount()
    {
        parent::mount();
        $this->sortField = 'namaFasilitas'; // Override default sort field
        $this->loadKamarOptions();
    }

    protected function loadKamarOptions()
    {
        $this->kamarOptions = Kamar::select('idKamar', 'nomorKamar')
            ->orderBy('nomorKamar')
            ->get()
            ->pluck('nomorKamar', 'idKamar')
            ->toArray();
    }

    public function updatingKamarFilter()
    {
        $this->resetPage();
    }

    public function updatingKondisiFilter()
    {
        $this->resetPage();
    }

    protected function getModelClass(): string
    {
        return FasilitasKamar::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.fasilitas-kamar-manager';
    }

    protected function getRecords()
    {
        return FasilitasKamar::with('kamar')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('namaFasilitas', 'like', '%' . $this->search . '%')
                      ->orWhere('kondisi', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kamar', function ($kamarQuery) {
                          $kamarQuery->where('nomorKamar', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->kamarFilter, function ($query) {
                $query->where('idKamar', $this->kamarFilter);
            })
            ->when($this->kondisiFilter, function ($query) {
                $query->where('kondisi', $this->kondisiFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    protected function resetForm(): void
    {
        $this->recordId = null;
        $this->idKamar = '';
        $this->namaFasilitas = '';
        $this->kondisi = 'baik';
    }

    protected function loadRecordData($record): void
    {
        $this->idKamar = $record->idKamar;
        $this->namaFasilitas = $record->namaFasilitas;
        $this->kondisi = $record->kondisi;
    }

    protected function getValidationRules(): array
    {
        return [
            'idKamar' => 'required|exists:kamar,idKamar',
            'namaFasilitas' => 'required|string|max:100',
            'kondisi' => 'required|in:' . implode(',', array_keys($this->kondisiOptions)),
        ];
    }

    protected function store(): ?Model
    {
        $data = [
            'idKamar' => $this->idKamar,
            'namaFasilitas' => $this->namaFasilitas,
            'kondisi' => $this->kondisi,
            'created_by' => auth()->id(),
        ];

        $fasilitas = FasilitasKamar::create($data);

        // Return the created record for global logging
        return $fasilitas;
    }

    protected function update(): ?Model
    {
        $fasilitas = FasilitasKamar::findOrFail($this->recordId);
        
        $data = [
            'idKamar' => $this->idKamar,
            'namaFasilitas' => $this->namaFasilitas,
            'kondisi' => $this->kondisi,
            'updated_by' => auth()->id(),
        ];

        $fasilitas->update($data);

        // Return the updated record for global logging
        return $fasilitas;
    }

    /**
     * Override getLogData untuk customize data yang di-log
     */
    protected function getLogData($record): array
    {
        $data = parent::getLogData($record);
        
        // Tambah informasi tambahan ke log
        $data['kondisi_label'] = $this->kondisiOptions[$record->kondisi] ?? $record->kondisi;
        $data['kamar_nomor'] = $record->kamar->nomorKamar ?? 'N/A';
        
        return $data;
    }

    protected function cannotDelete($record): bool
    {
        // Tidak ada kondisi khusus untuk tidak bisa hapus fasilitas
        return false;
    }

    protected function getAdditionalViewData(): array
    {
        return [
            'kondisiOptions' => $this->kondisiOptions,
            'kamarOptions' => $this->kamarOptions,
        ];
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah Fasilitas Kamar';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Fasilitas: ' . $record->namaFasilitas;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Fasilitas: ' . $record->namaFasilitas;
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Fasilitas kamar berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Fasilitas kamar berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Fasilitas kamar berhasil dihapus!';
    }

    // Helper method untuk badge kondisi
    public function getKondisiBadgeClass($kondisi)
    {
        return match($kondisi) {
            'baik' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'rusak_ringan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'rusak_berat' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'tidak_berfungsi' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    // Helper method untuk icon kondisi
    public function getKondisiIcon($kondisi)
    {
        return match($kondisi) {
            'baik' => '✓',
            'rusak_ringan' => '⚠',
            'rusak_berat' => '⚡',
            'tidak_berfungsi' => '✗',
            default => '?',
        };
    }
}