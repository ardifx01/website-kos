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

use App\Livewire\ComplaintManager;
use App\Services\ComplaintTokenService;
use App\Livewire\BookingFormManager;

use App\Livewire\PenyewaManager;
use App\Livewire\TipeKamarManager;

use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\PublicComplaintController;

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
    Route::get('/booking-form-manager', BookingFormManager::class)->name('booking-form-manager.index');
    Route::get('/penyewa-manager', PenyewaManager::class)->name('penyewa-manager.index');
    Route::get('/tipe-kamar-manager', TipeKamarManager::class)->name('tipe-kamar-manager.index');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});


Route::get('/complaint/{token}', [PublicComplaintController::class, 'show'])->name('public.complaint-form');
Route::post('/complaint/{token}', [PublicComplaintController::class, 'store'])->name('public.complaint-form.store');

Route::get('/booking/{token}', [PublicBookingController::class, 'show'])->name('public.booking-form');
Route::post('/booking/{token}', [PublicBookingController::class, 'store'])->name('public.booking-form.store');

require __DIR__.'/auth.php';