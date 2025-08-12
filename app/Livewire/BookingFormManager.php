<?php

namespace App\Livewire;

use App\Models\BookingForm;
use App\Models\User;
use App\Models\Penyewa;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BookingFormManager extends BaseTableManager
{
    // Properties specific to BookingForm model
    public $nama_lengkap;
    public $email;
    public $nomor_hp;
    public $jenis_kelamin;
    public $pekerjaan;
    public $alamat_ktp;
    public $alamat_domisili;
    public $tipe_kamar;
    public $jumlah_orang;
    public $tanggal_masuk;
    public $status_booking;
    public $catatan;
    public $statusFilter = '';
    public $showLinkModal = false;
    public $generatedLink = '';
    public $bookingForm; // Assuming you have a booking form model

    // Add status filter to query string
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'statusFilter' => ['except' => ''],
    ];

    public function mount()
    {
        parent::mount();
        $this->sortField = 'created_at'; // Override default sort field
        $this->sortDirection = 'desc';
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    protected function getModelClass(): string
    {
        return BookingForm::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.booking-form-manager';
    }

    protected function getRecords()
    {
        return BookingForm::with(['creator', 'updater'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nomor_hp', 'like', '%' . $this->search . '%')
                      ->orWhere('pekerjaan', 'like', '%' . $this->search . '%')
                      ->orWhere('tipe_kamar', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status_booking', $this->statusFilter);
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
        $this->jenis_kelamin = '';
        $this->pekerjaan = '';
        $this->alamat_ktp = '';
        $this->alamat_domisili = '';
        $this->tipe_kamar = '';
        $this->jumlah_orang = 1;
        $this->tanggal_masuk = '';
        $this->status_booking = 'pending';
        $this->catatan = '';
    }

    protected function loadRecordData($record): void
    {
        $this->nama_lengkap = $record->nama_lengkap;
        $this->email = $record->email;
        $this->nomor_hp = $record->nomor_hp;
        $this->jenis_kelamin = $record->jenis_kelamin;
        $this->pekerjaan = $record->pekerjaan;
        $this->alamat_ktp = $record->alamat_ktp;
        $this->alamat_domisili = $record->alamat_domisili;
        $this->tipe_kamar = $record->tipe_kamar;
        $this->jumlah_orang = $record->jumlah_orang;
        $this->tanggal_masuk = $record->tanggal_masuk;
        $this->status_booking = $record->status_booking;
        $this->catatan = $record->catatan;
    }

    protected function getValidationRules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_hp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'pekerjaan' => 'required|string|max:255',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'required|string',
            'tipe_kamar' => 'required|in:deluxe,premium',
            'jumlah_orang' => 'required|integer|min:1|max:10',
            'tanggal_masuk' => 'required|date|after_or_equal:today',
            'status_booking' => 'required|in:pending,approved,rejected,cancelled',
            'catatan' => 'nullable|string',
        ];
    }

    protected function store(): ?Model
    {
        $data = [
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'pekerjaan' => $this->pekerjaan,
            'alamat_ktp' => $this->alamat_ktp,
            'alamat_domisili' => $this->alamat_domisili,
            'tipe_kamar' => $this->tipe_kamar,
            'jumlah_orang' => $this->jumlah_orang,
            'tanggal_masuk' => $this->tanggal_masuk,
            'status_booking' => $this->status_booking,
            'catatan' => $this->catatan,
            'created_by' => auth()->id(),
        ];

        $booking = BookingForm::create($data);

        // Return the created record for global logging
        return $booking;
    }

    protected function update(): ?Model
    {
        $booking = BookingForm::findOrFail($this->recordId);
        
        $data = [
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'pekerjaan' => $this->pekerjaan,
            'alamat_ktp' => $this->alamat_ktp,
            'alamat_domisili' => $this->alamat_domisili,
            'tipe_kamar' => $this->tipe_kamar,
            'jumlah_orang' => $this->jumlah_orang,
            'tanggal_masuk' => $this->tanggal_masuk,
            'status_booking' => $this->status_booking,
            'catatan' => $this->catatan,
            'updated_by' => auth()->id(),
        ];

        $booking->update($data);

        // Return the updated record for global logging
        return $booking;
    }

    public function convertToPenyewa($id)
    {
        $booking = BookingForm::findOrFail($id);

        // Buat data penyewa baru dari booking
        $penyewa = Penyewa::create([
            'nama_lengkap'    => $booking->nama_lengkap,
            'email'           => $booking->email,
            'nomor_hp'        => $booking->nomor_hp,
            'jenis_kelamin'   => $booking->jenis_kelamin,
            'pekerjaan'       => $booking->pekerjaan,
            'alamat_ktp'      => $booking->alamat_ktp,
            'alamat_domisili' => $booking->alamat_domisili,
            'tipe_kamar'      => $booking->tipe_kamar,
            'jumlah_orang'    => $booking->jumlah_orang,
            'tanggal_masuk'   => $booking->tanggal_masuk,
            'status_sewa'     => 'aktif',
            'catatan'         => $booking->catatan,
            'booking_form_id' => $booking->id,
            'created_by'      => auth()->id(),
        ]);

        // Update status booking jadi approved (opsional)
        $booking->update([
            'status_booking' => 'approved',
            'updated_by' => auth()->id(),
        ]);

        // Tampilkan notifikasi sukses
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Booking berhasil diubah menjadi penyewa!'
        ]);

        return $penyewa;
    }

    public function generatePublicLink()
    {
        $token = Str::random(32);
        $publicUrl = route('public.booking-form', ['token' => $token]);
        
        // Store token in cache for validation (expires in 24 hours)
        cache()->put("booking_token_{$token}", true, now()->addHours(24));
        
        // Copy to clipboard using browser API
        $this->dispatch('copy-to-clipboard', text: $publicUrl);
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Link berhasil dibuat dan disalin ke clipboard!'
        ]);
    }

    protected function getAdditionalViewData(): array
    {
        return [
            'statusOptions' => [
                'pending' => 'Pending',
                'approved' => 'Approved', 
                'rejected' => 'Rejected',
                'cancelled' => 'Cancelled'
            ],
            'jenisKelaminOptions' => [
                'laki-laki' => 'Laki-laki',
                'perempuan' => 'Perempuan'
            ],
            'tipeKamarOptions' => [
                'deluxe' => 'Deluxe Room',
                'premium' => 'Premium Room',
            ]
        ];
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah Booking Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Booking: ' . $record->nama_lengkap;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Booking: ' . $record->nama_lengkap;
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Booking berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Booking berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Booking berhasil dihapus!';
    }
}