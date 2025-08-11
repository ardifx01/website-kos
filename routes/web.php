<?php
// routes/web.php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

use App\Livewire\UserManager;
use App\Livewire\RoleManager;
use App\Livewire\ActivityLogManager;
use App\Livewire\KamarManager;
use App\Livewire\FasilitasKamarManager;

use App\Livewire\KomplainForm;
use App\Livewire\ComplaintManager;
use App\Services\ComplaintTokenService;

// Root route
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('/users', UserManager::class)->name('users.index');
    Route::get('/roles', RoleManager::class)->name('roles.index');
    Route::get('/activity-log-manager', ActivityLogManager::class)->name('activity-log-manager.index');
    Route::get('/kamar-manager', KamarManager::class)->name('kamar-manager.index');
    Route::get('/fasilitas-kamar-manager', FasilitasKamarManager::class)->name('fasilitas-kamar-manager.index');
    Route::get('/complaint-manager', ComplaintManager::class)->name('complaint-manager.index');
    Route::get('/form-komplain', KomplainForm::class)->name('form-komplain.index');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Public complaint form routes
Route::get('/komplain', KomplainForm::class)->name('komplain-form');
Route::get('/complaint-form', KomplainForm::class)->name('complaint-form');

// Token-based complaint form route
Route::get('/form-komplain/{token?}', function ($token = null) {
    return app(\App\Livewire\KomplainForm::class, ['token' => $token]);
})->name('komplain.form.token');

// API endpoint for generating complaint links (for authenticated users)
Route::middleware(['auth'])->post('/api/generate-complaint-link', function (\Illuminate\Http\Request $request) {
    $type = $request->input('type', 'general');
    
    try {
        $linkData = match($type) {
            'qr-code' => ComplaintTokenService::generateQRCodeToken(),
            'email' => ComplaintTokenService::generateEmailToken(),
            'single-use' => ComplaintTokenService::generateSingleUseToken(),
            'staff' => ComplaintTokenService::generateStaffToken(),
            default => ComplaintTokenService::generateToken('general', 168)
        };
        
        return response()->json([
            'success' => true,
            'data' => $linkData,
            'message' => 'Link berhasil dibuat'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Failed to generate complaint link via API: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat link complaint'
        ], 500);
    }
})->name('api.generate.complaint.link');

// Public API endpoint for link generation (without authentication)
Route::get('/generate-complaint-link/{type?}', function ($type = 'general') {
    try {
        $linkData = match($type) {
            'qr-code' => ComplaintTokenService::generateQRCodeToken(),
            'email' => ComplaintTokenService::generateEmailToken(),
            'single-use' => ComplaintTokenService::generateSingleUseToken(),
            default => ComplaintTokenService::generateToken('general', 168)
        };
        
        return response()->json([
            'success' => true,
            'data' => $linkData,
            'message' => 'Link berhasil dibuat'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Failed to generate public complaint link: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat link complaint'
        ], 500);
    }
})->name('generate.complaint.link.public');

// API endpoint for validating tokens
Route::get('/api/validate-token/{token}', function ($token) {
    $validation = ComplaintTokenService::validateToken($token);
    
    return response()->json([
        'valid' => $validation['valid'],
        'data' => $validation['data'] ?? null,
        'reason' => $validation['reason'] ?? null
    ]);
})->name('api.validate.token');

// API routes
Route::prefix('api')->group(function () {
    // API endpoint to submit complaint (for mobile app or external integrations)
    Route::post('/complaints', function (\Illuminate\Http\Request $request) {
        try {
            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'nomor_hp' => 'required|string|max:20',
                'tipe_kamar' => 'required|string',
                'subjek' => 'required|string|max:255',
                'kategori' => 'required|string',
                'deskripsi' => 'required|string|min:10',
                'token' => 'nullable|string' // Optional token for tracking
            ]);
            
            // Add default status
            $validated['status_komplain'] = 'Pending';
            
            // Log API submission
            \Log::info('API complaint submission', [
                'email' => $validated['email'],
                'token' => $request->input('token'),
                'ip' => $request->ip()
            ]);
            
            $complaint = \App\Models\ComplaintForm::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Complaint submitted successfully',
                'id' => $complaint->id,
                'status' => $complaint->status_komplain
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('API complaint submission failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit complaint'
            ], 500);
        }
    })->name('api.complaints.store');
    
    // API endpoint to get complaint status
    Route::get('/complaints/{id}', function ($id) {
        try {
            $complaint = \App\Models\ComplaintForm::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $complaint->id,
                    'nama_lengkap' => $complaint->nama_lengkap,
                    'email' => $complaint->email,
                    'subjek' => $complaint->subjek,
                    'kategori' => $complaint->kategori,
                    'status_komplain' => $complaint->status_komplain,
                    'created_at' => $complaint->created_at,
                    'updated_at' => $complaint->updated_at
                ]
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve complaint'
            ], 500);
        }
    })->name('api.complaints.show');
});

require __DIR__.'/auth.php';