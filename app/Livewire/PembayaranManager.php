<?php

namespace App\Livewire;

use App\Models\Pembayaran;
use App\Models\BookingKamar;
use App\Models\User;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PembayaranManager extends BaseTableManager
{
    // Properties specific to Pembayaran model
    public $idBooking;
    public $idPenyewa;
    public $jumlah;
    public $tanggalBayar;
    public $metodePembayaran;
    public $status;
    public $buktiTransfer;
    public $statusFilter = '';
    public $metodeFilter = '';
    public $bookingOptions = [];
    public $penyewaOptions = [];
    public $showModal = false;
    public $showViewModal = false; // Ensure this property is defined

    public $recordId;
    public $selectedRecord;

    // Define the createTitle property
    public $createTitle = 'Tambah Pembayaran';

    // Define the editTitle property
    public $editTitle = 'Edit Pembayaran';

    // Define the viewTitle property
    public $viewTitle = 'Detail Pembayaran';

    // Add filters to query string
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'statusFilter' => ['except' => ''],
        'metodeFilter' => ['except' => ''],
    ];

    public function mount()
    {
        parent::mount();
        $this->sortField = 'created_at'; // Override default sort field
        $this->sortDirection = 'desc';
        $this->loadBookingOptions();
        $this->loadPenyewaOptions();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingMetodeFilter()
    {
        $this->resetPage();
    }

    protected function loadBookingOptions()
    {
        $this->bookingOptions = BookingKamar::with('penyewa')
            ->where('status', 'pending')          // <─ tambahkan
            ->get()
            ->mapWithKeys(fn ($b) => [
                $b->idBooking => "#{$b->idBooking} - {$b->penyewa?->nama_lengkap}"
            ])
            ->toArray();
    }

    protected function loadPenyewaOptions()
    {
        $this->penyewaOptions = BookingKamar::with('penyewa')
            ->get()
            ->mapWithKeys(function ($booking) {
                return [$booking->penyewa->id => $booking->penyewa->nama_lengkap];
            })
            ->toArray();
    }

    public function openCreateModal()
    {
        $this->showModal = true;
        $this->recordId = null; // Reset recordId for create mode
    }

    public function openEditModal($id)
    {
        $this->recordId = $id;
        $this->showModal = true;
        $this->loadRecordData();
    }

    public function openViewModal($id)
    {
        $this->selectedRecord = Pembayaran::find($id);
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    protected function getViewName(): string
    {
        return 'livewire.pembayaran-manager';
    }

    protected function getRecords()
    {
        return Pembayaran::with(['booking.penyewa', 'creator', 'updater'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('jumlah', 'like', '%' . $this->search . '%')
                      ->orWhere('metodePembayaran', 'like', '%' . $this->search . '%')
                      ->orWhereHas('booking.penyewa', function ($subQuery) {
                          $subQuery->where('nama_lengkap', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->metodeFilter, function ($query) {
                $query->where('metodePembayaran', $this->metodeFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    protected function resetForm(): void
    {
        $this->recordId = null;
        $this->idBooking = '';
        $this->idPenyewa = '';
        $this->jumlah = '';
        $this->tanggalBayar = '';
        $this->metodePembayaran = '';
        $this->status = 'pending';
        $this->buktiTransfer = '';
    }
    
    protected function getModelClass(): string
    {
        return \App\Models\Pembayaran::class;
    }
    // PembayaranManager.php
    protected function loadRecordData($record): void
    {
        $this->idBooking   = $record->idBooking;
        $this->jumlah      = $record->jumlah;
        $this->tanggalBayar = $record->tanggalBayar ? $record->tanggalBayar->format('Y-m-d') : '';
        $this->metodePembayaran = $record->metodePembayaran;
        $this->status      = $record->status;
        $this->buktiTransfer = $record->buktiTransfer;
    }

    protected function getValidationRules(): array
    {
        return [
            'idBooking' => 'required|exists:booking_kamar,idBooking',
            'idPenyewa' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:0',
            'tanggalBayar' => 'required|date',
            'metodePembayaran' => 'required|in:transfer_bank,cash,e_wallet,kartu_kredit',
            'status' => 'required|in:pending,berhasil,gagal,dibatalkan',
            'buktiTransfer' => 'nullable|string',
        ];
    }

    protected function store(): ?Model
    {
        $data = [
            'idBooking' => $this->idBooking,
            'jumlah' => $this->jumlah,
            'tanggalBayar' => $this->tanggalBayar,
            'metodePembayaran' => $this->metodePembayaran,
            'status' => $this->status,
            'buktiTransfer' => $this->buktiTransfer,
            'created_by' => auth()->id(),
        ];

        $pembayaran = Pembayaran::create($data);

        // Return the created record for global logging
        return $pembayaran;
    }

    protected function update(): ?Model
    {
        $pembayaran = Pembayaran::findOrFail($this->recordId);
        
        $data = [
            'idBooking' => $this->idBooking,
            'jumlah' => $this->jumlah,
            'tanggalBayar' => $this->tanggalBayar,
            'metodePembayaran' => $this->metodePembayaran,
            'status' => $this->status,
            'buktiTransfer' => $this->buktiTransfer,
            'updated_by' => auth()->id(),
        ];

        $pembayaran->update($data);

        // Return the updated record for global logging
        return $pembayaran;
    }

    public function confirmPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status' => 'berhasil',
            'updated_by' => auth()->id(),
        ]);

        // Update booking status if needed
        if ($pembayaran->booking) {
            $pembayaran->booking->update([
                'status' => 'confirmed',
                'updated_by' => auth()->id(),
            ]);
        }

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Pembayaran berhasil dikonfirmasi!'
        ]);

        return $pembayaran;
    }

    public function rejectPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status' => 'gagal',
            'updated_by' => auth()->id(),
        ]);

        $this->dispatch('show-alert', [
            'type' => 'warning',
            'message' => 'Pembayaran ditolak!'
        ]);

        return $pembayaran;
    }

    protected function getAdditionalViewData(): array
    {
        return [
            'statusOptions' => [
                'pending' => 'Pending',
                'berhasil' => 'Berhasil', 
                'gagal' => 'Gagal',
                'dibatalkan' => 'Dibatalkan'
            ],
            'metodePembayaranOptions' => [
                'transfer_bank' => 'Transfer Bank',
                'cash' => 'Cash/Tunai',
                'e_wallet' => 'E-Wallet',
                'kartu_kredit' => 'Kartu Kredit',
            ],
            'bookingOptions' => $this->bookingOptions,
            'penyewaOptions'  => $this->penyewaOptions,
        ];
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah Pembayaran Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Pembayaran: #' . $record->idPembayaran;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Pembayaran: #' . $record->idPembayaran;
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Pembayaran berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Pembayaran berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Pembayaran berhasil dihapus!';
    }
}