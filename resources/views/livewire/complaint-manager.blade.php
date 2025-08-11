<div>
    @php
        // Data untuk base table
        $pageTitle = 'Manajemen Complaint';
        $pageDescription = 'Kelola data complaint dari form publik';
        $searchPlaceholder = 'Cari nama, email, subjek, atau deskripsi...';
        $createButtonText = 'Complaint Form Link';
        $emptyStateTitle = 'Tidak ada complaint';
        $emptyStateDescription = 'Belum ada complaint yang masuk atau sesuai dengan pencarian Anda.';
        $tableColspan = 7;

    // Additional filters content
    $additionalFilters = '
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Status</label>
            <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Status</option>';
    
    foreach($this->statusOptions as $value => $label) {
        $additionalFilters .= '<option value="' . $value . '">' . $label . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Kategori</label>
            <select wire:model.live="kategoriFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Kategori</option>';
    
    foreach($this->kategoriOptions as $value => $label) {
        $additionalFilters .= '<option value="' . $value . '">' . $label . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
    </div>';

    // Custom create button (show complaint form link instead)
    $customCreateButton = '
    <div class="flex items-center space-x-3">
        <a href="' . route('komplain-form') . '" target="_blank"
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Buka Form Complaint
        </a>
        <button onclick="copyToClipboard(\'' . route('komplain-form') . '\')" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Copy Link
        </button>
        <button onclick="generateTokenLink()" 
                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Generate Token Link
        </button>
    </div>';

    // Table headers
    $tableHeaders = '
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'nama_lengkap\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Nama</span>
            ' . ($sortField === 'nama_lengkap' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kontak</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kamar</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subjek</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'created_at\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Tanggal</span>
            ' . ($sortField === 'created_at' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>';

    // Table row function
    $tableRow = function($complaint) {
        $statusMenuOptions = '';
        foreach($this->statusOptions as $status => $label) {
            if($status !== $complaint->status_komplain) {
                $statusMenuOptions .= '
                            <button wire:click="updateStatus(' . $complaint->id . ', \'' . $status . '\')" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                ' . $label . '
                            </button>';
            }
        }
        
        return '
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900 dark:text-white">' . $complaint->nama_lengkap . '</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900 dark:text-white">' . $complaint->email . '</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">' . $complaint->nomor_hp . '</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">' . $complaint->tipe_kamar . '</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                ' . $complaint->kategori . '
            </span>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-gray-900 dark:text-white max-w-xs truncate" title="' . $complaint->subjek . '">
                ' . $complaint->subjek . '
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center space-x-2">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $this->getStatusBadgeClass($complaint->status_komplain) . '">
                    ' . $complaint->status_komplain . '
                </span>
                ' . ($complaint->status_komplain !== 'Closed' ? '
                <div class="relative">
                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" onclick="toggleStatusMenu(' . $complaint->id . ')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="status-menu-' . $complaint->id . '" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-50 border border-gray-200 dark:border-gray-600">
                        <div class="py-1">
                            ' . $statusMenuOptions . '
                        </div>
                    </div>
                </div>
                ' : '') . '
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . $complaint->created_at->format('d/m/Y H:i') . '
        </td>';
    };

    // Modal content
    $modalContent = '
    <div class="space-y-6">
        <!-- Personal Information -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Informasi Personal</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Lengkap</label>
                    <p class="text-sm text-gray-900 dark:text-white">' . ($this->nama_lengkap ?? '-') . '</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                    <p class="text-sm text-gray-900 dark:text-white">' . ($this->email ?? '-') . '</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor HP</label>
                    <p class="text-sm text-gray-900 dark:text-white">' . ($this->nomor_hp ?? '-') . '</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipe Kamar</label>
                    <p class="text-sm text-gray-900 dark:text-white">' . ($this->tipe_kamar ?? '-') . '</p>
                </div>
            </div>
        </div>

        <!-- Complaint Information -->
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
                <p class="text-sm text-gray-900 dark:text-white">' . ($this->kategori ?? '-') . '</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Subjek</label>
                <p class="text-sm text-gray-900 dark:text-white">' . ($this->subjek ?? '-') . '</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Deskripsi</label>
                <div class="text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-600 p-3 rounded border max-h-40 overflow-y-auto">
                    ' . nl2br(e($this->deskripsi ?? '-')) . '
                </div>
            </div>
        </div>';

    if ($this->modalMode !== 'view') {
        $modalContent .= '
        <!-- Status Update -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
            <select wire:model="status_komplain"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Pilih Status</option>';
        
        foreach($this->statusOptions as $value => $label) {
            $modalContent .= '<option value="' . $value . '">' . $label . '</option>';
        }
        
        $modalContent .= '
            </select>
            ' . ($this->getErrorBag()->has('status_komplain') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('status_komplain') . '</span>' : '') . '
        </div>';
    }
    
    $modalContent .= '
    </div>';
@endphp

    {{-- Generate Token Link Section --}}
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Generate Complaint Form Links
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Buat link complaint form dengan berbagai tipe akses untuk dibagikan kepada pengguna
            </p>
        </div>
        
        <div class="p-6">
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <button wire:click="generateComplaintLink" 
                        class="flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    General Link
                </button>
                
                <button wire:click="generateQRLink" 
                        class="flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 01-3 0V5.478a1.5 1.5 0 01-3 0V4.5a1.5 1.5 0 113 0v5.478z"/>
                    </svg>
                    QR Code Link
                </button>
                
                <button wire:click="generateSingleUseLink" 
                        class="flex items-center justify-center px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Single-Use Link
                </button>
                
                @if(function_exists('route') && Route::has('komplain-form'))
                    <button onclick="copyToClipboard('{{ route('komplain-form') }}', 'Public form link copied!')" 
                            class="flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy Public Link
                    </button>
                @endif
            </div>

            <!-- Generated Links Table -->
            @if(isset($generatedLinks) && count($generatedLinks) > 0)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Generated Links History ({{ count($generatedLinks) }})
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Token</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Expires</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach(array_reverse($generatedLinks) as $link)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($link['type'] === 'general') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($link['type'] === 'qr-code') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($link['type'] === 'single-use') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                {{ ucfirst(str_replace('-', ' ', $link['type'])) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ Str::limit($link['token'], 20) }}</code>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($link['expires_at'])->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="copyToClipboard('{{ $link['link'] }}', 'Token link copied!')" 
                                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200" 
                                                        title="Copy Link">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>
                                                
                                                @if($link['type'] === 'qr-code')
                                                    <a href="{{ $link['qr_code_url'] }}" 
                                                       target="_blank" 
                                                       class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200"
                                                       title="View QR Code">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 01-3 0V5.478a1.5 1.5 0 01-3 0V4.5a1.5 1.5 0 113 0v5.478z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                                
                                                <a href="{{ $link['link'] }}" 
                                                   target="_blank" 
                                                   class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 transition-colors duration-200"
                                                   title="Test Link">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

{{-- Include base table with all configurations --}}
@include('livewire.base.base-table-manager', [
    'pageTitle' => $pageTitle,
    'pageDescription' => $pageDescription,
    'searchPlaceholder' => $searchPlaceholder,
    'createButtonText' => $createButtonText,
    'emptyStateTitle' => $emptyStateTitle,
    'emptyStateDescription' => $emptyStateDescription,
    'tableColspan' => $tableColspan,
    'additionalFilters' => $additionalFilters,
    'customCreateButton' => $customCreateButton,
    'tableHeaders' => $tableHeaders,
    'tableRow' => $tableRow,
    'modalContent' => $modalContent,
    'records' => $this->getRecords(),
    'selectedRecords' => $selectedRecords ?? [],
    'sortField' => $sortField ?? 'created_at',
    'sortDirection' => $sortDirection ?? 'desc',
    'showModal' => $showModal ?? false,
    'modalMode' => $modalMode ?? 'view',
    'modalTitle' => $modalTitle ?? 'Detail Complaint',
])

{{-- Link Generation Modal --}}
@if($showLinkModal && $currentGeneratedLink)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800" wire:click.stop>
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Complaint Link Generated
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="mt-4">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-green-800 dark:text-green-200 font-medium">Link berhasil dibuat dan siap dibagikan!</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 {{ $currentGeneratedLink['type'] === 'qr-code' ? 'lg:grid-cols-3' : '' }} gap-4">
                        <div class="{{ $currentGeneratedLink['type'] === 'qr-code' ? 'lg:col-span-2' : '' }}">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link URL:</label>
                                <div class="flex">
                                    <input type="text" 
                                           class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" 
                                           value="{{ $currentGeneratedLink['link'] }}" 
                                           readonly 
                                           id="generatedLink">
                                    <button onclick="copyToClipboard('{{ $currentGeneratedLink['link'] }}', 'Link copied!')" 
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-r-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Type:</span> 
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($currentGeneratedLink['type'] === 'general') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif($currentGeneratedLink['type'] === 'qr-code') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($currentGeneratedLink['type'] === 'single-use') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                        {{ ucfirst(str_replace('-', ' ', $currentGeneratedLink['type'])) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Expires:</span> 
                                    <span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($currentGeneratedLink['expires_at'])->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($currentGeneratedLink['type'] === 'qr-code')
                            <div class="text-center">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">QR Code:</label>
                                <div class="inline-block p-4 bg-white rounded-lg border border-gray-200 dark:border-gray-600">
                                    <img src="{{ $currentGeneratedLink['qr_code_url'] }}" 
                                         alt="QR Code" 
                                         class="w-32 h-32 mx-auto">
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Scan to access form</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600 mt-6">
                    <a href="{{ $currentGeneratedLink['link'] }}" 
                       target="_blank" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Test Link
                    </a>
                    <button wire:click="closeModal" 
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- JavaScript untuk functionality --}}
<script>
    function copyToClipboard(text, message = 'Link berhasil disalin ke clipboard!') {
        navigator.clipboard.writeText(text).then(function() {
            // Success notification
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 z-50';
            toast.innerHTML = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 3000);
        }).catch(function() {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            // Show fallback notification
            alert(message);
        });
    }

    function generateTokenLink() {
        const token = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        const tokenLink = '{{ url("/form-komplain") }}/' + token;
        copyToClipboard(tokenLink, 'Token link berhasil di-generate dan disalin!');
    }

    function toggleStatusMenu(id) {
        const menu = document.getElementById('status-menu-' + id);
        const allMenus = document.querySelectorAll('[id^="status-menu-"]');
        
        // Close all other menus
        allMenus.forEach(m => {
            if (m.id !== 'status-menu-' + id) {
                m.classList.add('hidden');
            }
        });
        
        // Toggle current menu
        menu.classList.toggle('hidden');
    }
    
    // Close menus when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[onclick^="toggleStatusMenu"]')) {
            const allMenus = document.querySelectorAll('[id^="status-menu-"]');
            allMenus.forEach(m => m.classList.add('hidden'));
        }
    });
</script>