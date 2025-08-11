<?php
// app/Livewire/ComplaintManager.php

namespace App\Livewire;

use App\Models\ComplaintForm;
use App\Livewire\Base\BaseTableManager;
use App\Services\ComplaintTokenService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ComplaintManager extends BaseTableManager
{
    // Properties from base ComplaintManager
    public $nama_lengkap;
    public $email;
    public $nomor_hp;
    public $tipe_kamar;
    public $subjek;
    public $kategori;
    public $deskripsi;
    public $status_komplain;
    
    // Filter properties
    public $statusFilter = '';
    public $kategoriFilter = '';

    // Status options
    public $statusOptions = [
        'Pending' => 'Pending',
        'In Progress' => 'In Progress', 
        'Resolved' => 'Resolved',
        'Closed' => 'Closed',
    ];

    // Category options
    public $kategoriOptions = [
        'Fasilitas Kamar' => 'Fasilitas Kamar',
        'Kebersihan' => 'Kebersihan',
        'Pelayanan Staff' => 'Pelayanan Staff',
        'Makanan & Minuman' => 'Makanan & Minuman',
        'Fasilitas Umum' => 'Fasilitas Umum',
        'Reservasi' => 'Reservasi',
        'Billing & Pembayaran' => 'Billing & Pembayaran',
        'Lainnya' => 'Lainnya',
    ];
    
    // Token generation properties
    public $generatedLinks = [];
    public $showLinkModal = false;
    public $currentGeneratedLink = null;

    // Add filters to query string
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'statusFilter' => ['except' => ''],
        'kategoriFilter' => ['except' => ''],
    ];

    public function mount()
    {
        parent::mount();
        $this->sortField = 'created_at'; // Default sort by newest
        $this->sortDirection = 'desc';
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingKategoriFilter()
    {
        $this->resetPage();
    }

    protected function getModelClass(): string
    {
        return ComplaintForm::class;
    }

    protected function getViewName(): string
    {
        return 'livewire.complaint-manager';
    }

    protected function getRecords()
    {
        return ComplaintForm::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nomor_hp', 'like', '%' . $this->search . '%')
                      ->orWhere('subjek', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status_komplain', $this->statusFilter);
            })
            ->when($this->kategoriFilter, function ($query) {
                $query->where('kategori', $this->kategoriFilter);
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
        $this->tipe_kamar = '';
        $this->subjek = '';
        $this->kategori = '';
        $this->deskripsi = '';
        $this->status_komplain = 'Pending';
    }

    protected function loadRecordData($record): void
    {
        $this->nama_lengkap = $record->nama_lengkap;
        $this->email = $record->email;
        $this->nomor_hp = $record->nomor_hp;
        $this->tipe_kamar = $record->tipe_kamar;
        $this->subjek = $record->subjek;
        $this->kategori = $record->kategori;
        $this->deskripsi = $record->deskripsi;
        $this->status_komplain = $record->status_komplain;
    }

    protected function getValidationRules(): array
    {
        return [
            'status_komplain' => 'required|in:Pending,In Progress,Resolved,Closed',
        ];
    }

    protected function store(): ?Model
    {
        // This method typically won't be used for complaints
        // as they are created through the public form
        return null;
    }

    protected function update(): ?Model
    {
        $complaint = ComplaintForm::findOrFail($this->recordId);
        
        $complaint->update([
            'status_komplain' => $this->status_komplain,
            'updated_by' => auth()->id(),
        ]);

        return $complaint;
    }

    public function updateStatus($id, $status)
    {
        $complaint = ComplaintForm::findOrFail($id);
        $complaint->update([
            'status_komplain' => $status,
            'updated_by' => auth()->id(),
        ]);

        $this->dispatch('refresh-table');
        $this->dispatch('success', 'Status complaint berhasil diupdate!');
    }

    protected function cannotDelete($record): bool
    {
        // Only allow deletion of resolved or closed complaints
        return !in_array($record->status_komplain, ['Resolved', 'Closed']);
    }
    
    protected function getCannotDeleteMessage($record): string
    {
        return 'Hanya complaint dengan status Resolved atau Closed yang dapat dihapus!';
    }

    // Override to disable create functionality
    public function create()
    {
        $this->dispatch('info', 'Complaint dibuat melalui form publik, bukan melalui admin panel.');
    }

    // Override titles and messages
    protected function getEditTitle($record): string
    {
        return 'Update Status Complaint: ' . $record->subjek;
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Complaint: ' . $record->subjek;
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Status complaint berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Complaint berhasil dihapus!';
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'Pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'In Progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'Resolved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'Closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }
    
    /**
     * Generate general complaint form link
     */
    public function generateComplaintLink()
    {
        try {
            $linkData = ComplaintTokenService::generateToken('general', 168); // 7 days
            
            $this->currentGeneratedLink = $linkData;
            $this->generatedLinks[] = $linkData;
            $this->showLinkModal = true;
            
            $this->dispatch('link-generated', $linkData);
            session()->flash('success', 'Link komplain berhasil dibuat!');
            
        } catch (\Exception $e) {
            Log::error('Failed to generate complaint link: ' . $e->getMessage());
            session()->flash('error', 'Gagal membuat link komplain.');
        }
    }
    
    /**
     * Generate QR Code link
     */
    public function generateQRLink()
    {
        try {
            $linkData = ComplaintTokenService::generateQRCodeToken();
            
            $this->currentGeneratedLink = $linkData;
            $this->generatedLinks[] = $linkData;
            $this->showLinkModal = true;
            
            $this->dispatch('qr-link-generated', $linkData);
            session()->flash('success', 'QR Code link berhasil dibuat!');
            
        } catch (\Exception $e) {
            Log::error('Failed to generate QR link: ' . $e->getMessage());
            session()->flash('error', 'Gagal membuat QR Code link.');
        }
    }
    
    /**
     * Generate single use link
     */
    public function generateSingleUseLink()
    {
        try {
            $linkData = ComplaintTokenService::generateSingleUseToken();
            
            $this->currentGeneratedLink = $linkData;
            $this->generatedLinks[] = $linkData;
            $this->showLinkModal = true;
            
            $this->dispatch('single-use-link-generated', $linkData);
            session()->flash('success', 'Single-use link berhasil dibuat!');
            
        } catch (\Exception $e) {
            Log::error('Failed to generate single-use link: ' . $e->getMessage());
            session()->flash('error', 'Gagal membuat single-use link.');
        }
    }
    
    /**
     * Copy link to clipboard (handled by frontend)
     */
    public function copyToClipboard($link)
    {
        $this->dispatch('copy-to-clipboard', $link);
    }
    
    /**
     * Close modal
     */
    public function closeModal()
    {
        $this->showLinkModal = false;
        $this->currentGeneratedLink = null;
    }
    
    public function render()
    {
        return view('livewire.complaint-manager');
    }
}