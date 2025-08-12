<?php

namespace App\Livewire;

use App\Models\Penyewa;
use App\Models\BookingForm;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class PenyewaManager extends BaseTableManager
{
    // Properties specific to Penyewa model
    public $nama_lengkap;
    public $email;
    public $nomor_hp;
    public $pekerjaan;
    public $alamat_ktp;
    public $alamat_domisili;
    public $tipe_kamar;
    public $tanggal_masuk;
    public $status_sewa;
    public $catatan;
    public $booking_form_id;
    
    // Filters
    public $statusFilter = '';
    public $tipeKamarFilter = '';
    public $jenisKelaminFilter = '';

    // Add filters to query string
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'statusFilter' => ['except' => ''],
        'tipeKamarFilter' => ['except' => ''],
        'jenisKelaminFilter' => ['except' => ''],
    ];

    public function mount()
    {
        parent::mount();
        $this->sortField = 'nama_lengkap'; // Override default sort field
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTipeKamarFilter()
    {
        $this->resetPage();
    }

    public function updatingJenisKelaminFilter()
    {
        $this->resetPage();
    }

    protected function getModelClass(): string
    {
        return Penyewa::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.penyewa-manager';
    }

    protected function getRecords()
    {
        return Penyewa::with('bookingForm', 'creator')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nomor_hp', 'like', '%' . $this->search . '%')
                      ->orWhere('pekerjaan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status_sewa', $this->statusFilter);
            })
            ->when($this->tipeKamarFilter, function ($query) {
                $query->where('tipe_kamar', $this->tipeKamarFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    protected function resetForm(): void
    {
        $this->recordId = null;
        $this->nama_lengkap = '';
        $this->email = '';
        $this->nomor_hp = '';
        $this->pekerjaan = '';
        $this->alamat_ktp = '';
        $this->alamat_domisili = '';
        $this->tipe_kamar = '';
        $this->tanggal_masuk = '';
        $this->status_sewa = 'aktif';
        $this->catatan = '';
        $this->booking_form_id = '';
    }

    protected function loadRecordData($record): void
    {
        $this->nama_lengkap = $record->nama_lengkap;
        $this->email = $record->email;
        $this->nomor_hp = $record->nomor_hp;
        $this->pekerjaan = $record->pekerjaan;
        $this->alamat_ktp = $record->alamat_ktp;
        $this->alamat_domisili = $record->alamat_domisili;
        $this->tipe_kamar = $record->tipe_kamar;
        $this->tanggal_masuk = $record->tanggal_masuk?->format('Y-m-d');
        $this->status_sewa = $record->status_sewa;
        $this->catatan = $record->catatan;
        $this->booking_form_id = $record->booking_form_id;
    }

    protected function getValidationRules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('penyewas', 'email')->ignore($this->recordId),
            ],
            'nomor_hp' => 'required|string|max:20',
            'pekerjaan' => 'required|string|max:255',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'nullable|string',
            'tipe_kamar' => 'required|in:deluxe,premium',
            'tanggal_masuk' => 'required|date',
            'status_sewa' => 'required|in:aktif,tidak_aktif,keluar',
            'catatan' => 'nullable|string',
            'booking_form_id' => 'nullable|exists:booking_forms,id',
        ];
    }

    protected function store(): ?Model
    {
        $data = [
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'pekerjaan' => $this->pekerjaan,
            'alamat_ktp' => $this->alamat_ktp,
            'alamat_domisili' => $this->alamat_domisili,
            'tipe_kamar' => $this->tipe_kamar,
            'tanggal_masuk' => $this->tanggal_masuk,
            'status_sewa' => $this->status_sewa,
            'catatan' => $this->catatan,
            'booking_form_id' => $this->booking_form_id ?: null,
            'created_by' => auth()->id(),
        ];

        $penyewa = Penyewa::create($data);

        // Return the created record for global logging
        return $penyewa;
    }

    protected function update(): ?Model
    {
        $penyewa = Penyewa::findOrFail($this->recordId);
        
        $data = [
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'pekerjaan' => $this->pekerjaan,
            'alamat_ktp' => $this->alamat_ktp,
            'alamat_domisili' => $this->alamat_domisili,
            'tipe_kamar' => $this->tipe_kamar,
            'tanggal_masuk' => $this->tanggal_masuk,
            'status_sewa' => $this->status_sewa,
            'catatan' => $this->catatan,
            'booking_form_id' => $this->booking_form_id ?: null,
            'updated_by' => auth()->id(),
        ];

        $penyewa->update($data);

        // Return the updated record for global logging
        return $penyewa;
    }

    /**
     * Override getLogData untuk customize data yang di-log
     * Menambahkan informasi booking form dan menghapus data sensitif
     */
    protected function getLogData($record): array
    {
        $data = parent::getLogData($record);
        
        // Tambah informasi booking form ke log
        if ($record->bookingForm) {
            $data['booking_form_name'] = $record->bookingForm->nama_lengkap;
        }
        
        return $data;
    }

    protected function cannotDelete($record): bool
    {
        // Tidak bisa hapus penyewa yang masih aktif
        if ($record->status_sewa === 'aktif') {
            return true;
        }
        
        return false;
    }
    
    protected function getCannotDeleteMessage($record): string
    {
        if ($record->status_sewa === 'aktif') {
            return 'Tidak dapat menghapus penyewa yang masih aktif!';
        }
        
        return parent::getCannotDeleteMessage($record);
    }

    protected function getAdditionalViewData(): array
    {
        return [
            'bookingForms' => BookingForm::whereNull('penyewa_id')->get(),
            'tipeKamarOptions' => [
                'deluxe' => 'Deluxe',
                'premium' => 'Premium'
            ],
            'statusSewaOptions' => [
                'aktif' => 'Aktif',
                'tidak_aktif' => 'Tidak Aktif',
                'keluar' => 'Keluar'
            ],
            'jenisKelaminOptions' => [
                'perempuan' => 'Perempuan'
            ]
        ];
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah Penyewa Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Penyewa: ' . $record->nama_lengkap;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Penyewa: ' . $record->nama_lengkap;
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Penyewa berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Data penyewa berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Penyewa berhasil dihapus!';
    }
}