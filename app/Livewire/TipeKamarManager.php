<?php

namespace App\Livewire;

use App\Models\TipeKamar;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class TipeKamarManager extends BaseTableManager
{
    // Properties specific to TipeKamar model
    public $nama;
    public $deskripsi;
    public $hargaSewa;

    public function mount()
    {
        parent::mount();
        $this->sortField = 'nama'; // Override default sort field
    }

    protected function getModelClass(): string
    {
        return TipeKamar::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.tipe-kamar-manager';
    }

    protected function getRecords()
    {
        return TipeKamar::with(['creator', 'kamars'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                      ->orWhere('hargaSewa', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    protected function resetForm(): void
    {
        $this->recordId = null;
        $this->nama = '';
        $this->deskripsi = '';
        $this->hargaSewa = '';
    }

    protected function loadRecordData($record): void
    {
        $this->nama = $record->nama;
        $this->deskripsi = $record->deskripsi;
        $this->hargaSewa = $record->hargaSewa;
    }

    protected function getValidationRules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipe_kamar', 'nama')->ignore($this->recordId),
            ],
            'deskripsi' => 'nullable|string',
            'hargaSewa' => 'required|numeric|min:0',
        ];
    }

    protected function store(): ?Model
    {
        $data = [
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'hargaSewa' => $this->hargaSewa,
            'created_by' => auth()->id(),
        ];

        $tipeKamar = TipeKamar::create($data);

        // Return the created record for global logging
        return $tipeKamar;
    }

    protected function update(): ?Model
    {
        $tipeKamar = TipeKamar::findOrFail($this->recordId);
        
        $data = [
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'hargaSewa' => $this->hargaSewa,
            'updated_by' => auth()->id(),
        ];

        $tipeKamar->update($data);

        // Return the updated record for global logging
        return $tipeKamar;
    }

    protected function cannotDelete($record): bool
    {
        // Tidak bisa hapus tipe kamar yang masih memiliki kamar aktif
        return $record->kamars()->count() > 0;
    }
    
    protected function getCannotDeleteMessage($record): string
    {
        if ($record->kamars()->count() > 0) {
            return 'Tipe kamar tidak dapat dihapus karena masih memiliki ' . $record->kamars()->count() . ' kamar aktif!';
        }
        
        return parent::getCannotDeleteMessage($record);
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah Tipe Kamar Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Tipe Kamar: ' . $record->nama;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Tipe Kamar: ' . $record->nama;
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Tipe kamar berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Tipe kamar berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Tipe kamar berhasil dihapus!';
    }

    // Helper method to format currency
    public function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}