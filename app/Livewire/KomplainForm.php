<?php
// app/Livewire/KomplainForm.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ComplaintForm;
use App\Services\ComplaintTokenService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class KomplainForm extends Component
{
    // Form properties
    public $nama_lengkap = '';
    public $email = '';
    public $nomor_hp = '';
    public $tipe_kamar = '';
    public $subjek = '';
    public $kategori = '';
    public $deskripsi = '';
    
    // UI states
    public $showSuccessMessage = false;
    public $isSubmitted = false;
    public $isLoading = false;
    
    // Token handling
    public $token = null;
    public $tokenData = null;
    public $accessType = 'public'; // public, token, restricted

    // Link generator properties
    public $showLinkGenerator = false;
    public $generatedLinks = [];

    // Available options
    public $tipeKamarOptions = [
        'Standard' => 'Standard',
        'Superior' => 'Superior',
        'Deluxe' => 'Deluxe',
        'Suite' => 'Suite',
        'Family Room' => 'Family Room',
        'Twin Bed' => 'Twin Bed',
        'Double Bed' => 'Double Bed',
    ];

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

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'nomor_hp' => 'required|string|max:20',
        'tipe_kamar' => 'required|string',
        'subjek' => 'required|string|max:255',
        'kategori' => 'required|string',
        'deskripsi' => 'required|string|min:10',
    ];

    protected $messages = [
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'nomor_hp.required' => 'Nomor HP wajib diisi.',
        'tipe_kamar.required' => 'Tipe kamar wajib dipilih.',
        'subjek.required' => 'Subjek complaint wajib diisi.',
        'kategori.required' => 'Kategori complaint wajib dipilih.',
        'deskripsi.required' => 'Deskripsi complaint wajib diisi.',
        'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
    ];

    public function mount($token = null)
    {
        $this->token = $token;
        
        if ($token) {
            $validation = ComplaintTokenService::validateToken($token);
            
            if (!$validation['valid']) {
                // Redirect to public form with error message
                session()->flash('token_error', $validation['reason']);
                return redirect()->route('komplain-form');
            }
            
            $this->tokenData = $validation['data'];
            $this->accessType = 'token';
            
            // Pre-fill some data based on token type if needed
            if ($this->tokenData['type'] === 'staff') {
                $this->kategori = 'Lainnya'; // Staff can report any category
            }
        }
        
        // Log form access
        Log::info('Complaint form accessed', [
            'token' => $token,
            'access_type' => $this->accessType,
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function toggleLinkGenerator()
    {
        $this->showLinkGenerator = !$this->showLinkGenerator;
    }

    /**
     * Generate token link based on type
     */
    public function generateTokenLink($type = 'general')
    {
        try {
            $linkData = match($type) {
                'qr-code' => ComplaintTokenService::generateQRCodeToken(),
                'email' => ComplaintTokenService::generateEmailToken(),
                'single-use' => ComplaintTokenService::generateSingleUseToken(),
                'staff' => ComplaintTokenService::generateStaffToken(),
                default => ComplaintTokenService::generateToken('general', 168)
            };

            // Add generated timestamp for display
            $linkData['generated_at'] = now()->format('d M Y, H:i');
            
            $this->generatedLinks[] = $linkData;
            
            $this->dispatch('linkGenerated', $linkData);
            
            Log::info('Token link generated from form', [
                'type' => $type,
                'token' => $linkData['token'],
                'ip' => Request::ip()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to generate token link: ' . $e->getMessage());
            session()->flash('error', 'Gagal membuat token link.');
        }
    }

    public function submit()
    {
        // Rate limiting logic - different limits for token vs public access
        if ($this->accessType === 'token') {
            $rateLimitKey = 'complaint-token:' . $this->token;
            $maxAttempts = $this->tokenData['type'] === 'single-use' ? 1 : 10;
        } else {
            $rateLimitKey = 'complaint-public:' . Request::ip();
            $maxAttempts = 3;
        }
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            $this->addError('rate_limit', 'Terlalu banyak pengajuan complaint. Silakan coba lagi nanti.');
            return;
        }

        $this->isLoading = true;

        try {
            $this->validate();

            // Create complaint with token information
            $complaintData = [
                'nama_lengkap' => $this->nama_lengkap,
                'email' => $this->email,
                'nomor_hp' => $this->nomor_hp,
                'tipe_kamar' => $this->tipe_kamar,
                'subjek' => $this->subjek,
                'kategori' => $this->kategori,
                'deskripsi' => $this->deskripsi,
                'status_komplain' => 'Pending',
            ];

            $complaint = ComplaintForm::create($complaintData);

            // Log complaint creation with token info
            Log::info('Complaint submitted', [
                'complaint_id' => $complaint->id,
                'token' => $this->token,
                'access_type' => $this->accessType,
                'token_type' => $this->tokenData['type'] ?? null,
                'email' => $this->email,
                'kategori' => $this->kategori,
            ]);

            // Increment rate limiter
            RateLimiter::hit($rateLimitKey, $this->accessType === 'token' ? 3600 : 3600);

            // Reset form
            $this->resetForm();
            
            // Show success message
            $this->showSuccessMessage = true;
            $this->isSubmitted = true;

        } catch (\Exception $e) {
            Log::error('Complaint submission failed', [
                'error' => $e->getMessage(),
                'token' => $this->token,
                'email' => $this->email,
            ]);
            
            $this->addError('submit', 'Terjadi kesalahan saat mengirim complaint. Silakan coba lagi.');
        } finally {
            $this->isLoading = false;
        }
    }

    public function resetForm()
    {
        $this->nama_lengkap = '';
        $this->email = '';
        $this->nomor_hp = '';
        $this->tipe_kamar = '';
        $this->subjek = '';
        $this->kategori = '';
        $this->deskripsi = '';
        $this->resetErrorBag();
    }

    public function submitAnother()
    {
        // Check if token allows multiple submissions
        if ($this->tokenData && $this->tokenData['type'] === 'single-use') {
            session()->flash('token_error', 'Token ini hanya dapat digunakan sekali.');
            return redirect()->route('komplain-form');
        }
        
        $this->showSuccessMessage = false;
        $this->isSubmitted = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.komplain-form')
            ->layout('components.layouts.guest', [
                'title' => 'Form Complaint' . ($this->token ? ' (Token Access)' : ''),
                'description' => 'Sampaikan keluhan Anda kepada kami'
            ]);
    }

    // Helper method to get token info for view
    public function getTokenInfo()
    {
        if (!$this->tokenData) return null;
        
        return [
            'type' => $this->tokenData['type'],
            'expires_at' => $this->tokenData['expires_at'],
            'used_count' => $this->tokenData['used_count'],
            'max_usage' => $this->tokenData['max_usage'] ?? 'Unlimited',
        ];
    }
}