<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Form Komplain</h1>
            <p class="text-gray-600 dark:text-gray-300">Sampaikan keluhan Anda kepada kami. Kami akan menindaklanjuti dalam 24 jam.</p>
            
            <!-- Link Generator Toggle Button -->
            <!-- <div class="mt-4">
                <button wire:click="toggleLinkGenerator" 
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    {{ $showLinkGenerator ? 'Sembunyikan' : 'Generate Link' }}
                </button>
            </div> -->

            
        </div>
        <!-- <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">Informasi Penting</h4>
                        <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-1">
                            <li>• Tim kami akan merespons dalam waktu maksimal 24 jam</li>
                            <li>• Pastikan email dan nomor HP yang Anda berikan aktif</li>
                            <li>• Untuk keluhan mendesak, hubungi resepsionis langsung</li>
                            <li>• Anda dapat mengirim maksimal 3 komplain per jam</li>
                        </ul>
                    </div>
                </div>
            </div> -->

        <!-- Link Generator Section -->
        @if($showLinkGenerator)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="p-6 bg-gradient-to-r from-purple-500 to-indigo-600">
                    <h3 class="text-lg font-semibold text-white mb-2">Generator Link Complaint</h3>
                    <p class="text-purple-100 text-sm">Buat link khusus untuk berbagai keperluan</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <button wire:click="generateTokenLink('general')" 
                                class="flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Link Umum
                        </button>
                        
                        <button wire:click="generateTokenLink('qr-code')" 
                                class="flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 01-3 0V5.478a1.5 1.5 0 01-3 0V4.5a1.5 1.5 0 113 0v5.478z"/>
                            </svg>
                            Link QR Code
                        </button>
                        
                        <button wire:click="generateTokenLink('email')" 
                                class="flex items-center justify-center px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Link Email
                        </button>
                        
                        <button wire:click="generateTokenLink('single-use')" 
                                class="flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Link Sekali Pakai
                        </button>
                    </div>

                    <!-- Generated Links Display -->
                    @if(count($generatedLinks) > 0)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Link yang Dibuat:</h4>
                            <div class="space-y-4 max-h-64 overflow-y-auto">
                                @foreach($generatedLinks as $index => $linkData)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($linkData['type'] === 'general') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($linkData['type'] === 'qr-code') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($linkData['type'] === 'email') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst(str_replace('-', ' ', $linkData['type'])) }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $linkData['generated_at'] }}</span>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2 mb-2">
                                            <input type="text" value="{{ $linkData['link'] }}" readonly
                                                   class="flex-1 px-3 py-2 text-sm bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 dark:text-white">
                                            <button onclick="copyToClipboard('{{ $linkData['link'] }}')" 
                                                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        @if($linkData['type'] === 'qr-code')
                                            <div class="mt-3 text-center">
                                                <img src="{{ $linkData['qr_code_url'] }}" alt="QR Code" class="mx-auto rounded-lg shadow-md">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">QR Code untuk akses cepat</p>
                                            </div>
                                        @endif
                                        
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                            <strong>Token:</strong> {{ $linkData['token'] }}<br>
                                            <strong>Expired:</strong> {{ \Carbon\Carbon::parse($linkData['expires_at'])->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if ($showSuccessMessage && $isSubmitted)
            <!-- Success Message -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full mb-4">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Komplain Berhasil Dikirim!</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Terima kasih telah menyampaikan keluhan Anda. Tim kami akan menindaklanjuti dalam waktu 24 jam dan menghubungi Anda melalui email atau nomor HP yang telah Anda berikan.
                    </p>
                    <button wire:click="submitAnother" 
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Kirim Komplain Lain
                    </button>
                </div>
            </div>
        @else
            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <!-- Flash Messages -->
                    @if (session()->has('success'))
                        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-green-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <p class="text-sm text-green-800 dark:text-green-400">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-red-800 dark:text-red-400">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Rate Limit Error -->
                    @error('rate_limit')
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-red-800 dark:text-red-400">{{ $message }}</p>
                            </div>
                        </div>
                    @enderror

                    <!-- General Submit Error -->
                    @error('submit')
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-red-800 dark:text-red-400">{{ $message }}</p>
                            </div>
                        </div>
                    @enderror

                    <form wire:submit.prevent="submit" class="space-y-6">
                        <!-- Personal Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Personal</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama Lengkap -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model.live="nama_lengkap"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                           placeholder="Masukkan nama lengkap Anda">
                                    @error('nama_lengkap')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" wire:model.live="email"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                           placeholder="contoh@email.com">
                                    @error('email')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Nomor HP -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nomor HP <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model.live="nomor_hp"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                           placeholder="08xxxxxxxxxx">
                                    @error('nomor_hp')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Tipe Kamar -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tipe Kamar <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model.live="tipe_kamar"
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                        <option value="">Pilih tipe kamar</option>
                                        @foreach($this->tipeKamarOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipe_kamar')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Complaint Information Section -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Komplain</h3>
                            
                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kategori Komplain <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="kategori"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                    <option value="">Pilih kategori komplain</option>
                                    @foreach($this->kategoriOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('kategori')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Subjek -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Subjek Komplain <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model.live="subjek"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                       placeholder="Ringkasan singkat keluhan Anda">
                                @error('subjek')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Deskripsi Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model.live="deskripsi" rows="6"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200 resize-none"
                                          placeholder="Jelaskan keluhan Anda secara detail..."></textarea>
                                <div class="flex justify-between items-center mt-1">
                                    @error('deskripsi')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Minimal 10 karakter</span>
                                    @enderror
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ strlen($deskripsi) }} karakter</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="w-full flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <span wire:loading.remove>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Kirim Komplain
                                </span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Mengirim...
                                </span>
                            </button>
                        </div>

                        <!-- Footer Info -->
                        <!-- <div class="text-center pt-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Dengan mengirim form ini, Anda setuju bahwa data akan diproses untuk menindaklanjuti keluhan Anda.
                            </p>
                        </div> -->
                    </form>
                </div>
            </div>
        @endif

        <!-- Additional Info Card -->
        
    </div>
</div>

<script>
    // Copy to clipboard function
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                showToast('Link berhasil disalin ke clipboard!', 'success');
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                fallbackCopyTextToClipboard(text);
            });
        } else {
            // Fallback for older browsers or non-secure contexts
            fallbackCopyTextToClipboard(text);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showToast('Link berhasil disalin ke clipboard!', 'success');
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            showToast('Gagal menyalin link. Silakan salin secara manual.', 'error');
        }
        
        document.body.removeChild(textArea);
    }

    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium shadow-lg transform transition-all duration-300 ease-in-out translate-x-full`;
        
        // Set background color based on type
        switch(type) {
            case 'success':
                toast.className += ' bg-green-500';
                break;
            case 'error':
                toast.className += ' bg-red-500';
                break;
            default:
                toast.className += ' bg-blue-500';
        }
        
        toast.textContent = message;
        
        // Add to DOM
        document.body.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Listen for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('linkGenerated', (event) => {
            showToast('Link berhasil dibuat!', 'success');
        });
    });
</script>