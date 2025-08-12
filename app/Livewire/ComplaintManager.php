<?php

namespace App\Livewire;

use App\Models\ComplaintForm;
use App\Models\User;
use App\Livewire\Base\BaseTableManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ComplaintManager extends BaseTableManager
{
    // Properties specific to ComplaintForm model
    public $nama_lengkap;
    public $email;
    public $nomor_hp;
    public $tipe_kamar;
    public $subjek;
    public $kategori;
    public $deskripsi;
    public $status_komplain;
    public $token_used;
    public $admin_response;
    public $responded_at;
    public $responded_by;
    public $showLinkModal = false;
    public $generatedLink = '';
    public $complaintForm; // Assuming you have a complaint form model
    
    // Filters
    public $statusFilter = '';
    public $kategoriFilter = '';

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
        $this->sortField = 'created_at'; // Override default sort field
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
        return ComplaintForm::with(['creator', 'updater', 'respondedBy'])
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
                $query->byStatus($this->statusFilter);
            })
            ->when($this->kategoriFilter, function ($query) {
                $query->byKategori($this->kategoriFilter);
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
        $this->status_komplain = 'Open';
        $this->token_used = '';
        $this->admin_response = '';
        $this->responded_at = null;
        $this->responded_by = null;
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
        $this->token_used = $record->token_used;
        $this->admin_response = $record->admin_response;
        $this->responded_at = $record->responded_at?->format('Y-m-d\TH:i');
        $this->responded_by = $record->responded_by;
    }

    protected function getValidationRules(): array
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_hp' => 'required|string|max:20',
            'tipe_kamar' => 'required|in:' . implode(',', array_keys(ComplaintForm::getTipeKamarOptions())),
            'subjek' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', array_keys(ComplaintForm::getKategoriOptions())),
            'deskripsi' => 'required|string',
            'status_komplain' => 'required|in:' . implode(',', array_keys(ComplaintForm::getStatusOptions())),
            'admin_response' => 'nullable|string',
            'responded_at' => 'nullable|date',
            'responded_by' => 'nullable|exists:users,id',
        ];

        return $rules;
    }

    protected function store(): ?Model
    {
        $data = [
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'tipe_kamar' => $this->tipe_kamar,
            'subjek' => $this->subjek,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'status_komplain' => $this->status_komplain,
            'admin_response' => $this->admin_response,
            'responded_at' => $this->responded_at ? now() : null,
            'responded_by' => $this->admin_response ? auth()->id() : null,
        ];

        $complaint = ComplaintForm::create($data);

        // Return the created record for global logging
        return $complaint;
    }

    protected function update(): ?Model
    {
        $complaint = ComplaintForm::findOrFail($this->recordId);
        
        $data = [
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'tipe_kamar' => $this->tipe_kamar,
            'subjek' => $this->subjek,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'status_komplain' => $this->status_komplain,
            'admin_response' => $this->admin_response,
        ];

        // Update response info if admin_response is provided
        if ($this->admin_response && !$complaint->admin_response) {
            $data['responded_at'] = now();
            $data['responded_by'] = auth()->id();
        }

        $complaint->update($data);

        // Return the updated record for global logging
        return $complaint;
    }

    public function generatePublicLink()
    {
        $token = Str::random(32);
        $publicUrl = route('public.complaint-form', ['token' => $token]);
        
        // Store token in cache for validation (expires in 24 hours)
        cache()->put("complaint_token_{$token}", true, now()->addHours(24));
        
        // Copy to clipboard using browser API
        $this->dispatch('copy-to-clipboard', text: $publicUrl);
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Link complaint form berhasil dibuat dan disalin ke clipboard!'
        ]);
    }

    public function respondToComplaint()
    {
        $this->validate([
            'admin_response' => 'required|string|min:10',
            'status_komplain' => 'required|in:In Progress,Resolved,Closed'
        ]);

        $complaint = ComplaintForm::findOrFail($this->recordId);
        
        $complaint->update([
            'admin_response' => $this->admin_response,
            'status_komplain' => $this->status_komplain,
            'responded_at' => now(),
            'responded_by' => auth()->id(),
        ]);

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Response berhasil dikirim!'
        ]);

        $this->closeModal();
        $this->resetForm();
    }

    protected function getAdditionalViewData(): array
    {
        return [
            'statusOptions' => ComplaintForm::getStatusOptions(),
            'kategoriOptions' => ComplaintForm::getKategoriOptions(),
            'tipeKamarOptions' => ComplaintForm::getTipeKamarOptions(),
            'users' => User::all(),
        ];
    }

    // Override titles and messages
    protected function getCreateTitle(): string
    {
        return 'Tambah Complaint Baru';
    }

    protected function getEditTitle($record): string
    {
        return 'Edit Complaint: ' . Str::limit($record->subjek, 30);
    }

    protected function getViewTitle($record): string
    {
        return 'Detail Complaint: ' . Str::limit($record->subjek, 30);
    }

    protected function getCreateSuccessMessage(): string
    {
        return 'Complaint berhasil ditambahkan!';
    }

    protected function getUpdateSuccessMessage(): string
    {
        return 'Complaint berhasil diupdate!';
    }

    protected function getDeleteSuccessMessage(): string
    {
        return 'Complaint berhasil dihapus!';
    }
}