@php
    // Data untuk base table
    $pageTitle = 'Manajemen Complaint';
    $pageDescription = 'Kelola complaint dan keluhan pelanggan';
    $searchPlaceholder = 'Cari nama, email, subjek, atau deskripsi...';
    $createButtonText = 'Tambah Complaint';
    $emptyStateTitle = 'Tidak ada complaint';
    $emptyStateDescription = 'Belum ada complaint yang masuk atau sesuai dengan pencarian Anda.';
    $tableColspan = 8;

    // Additional filters content
    $additionalFilters = '
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Status</label>
            <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Status</option>';
    
    foreach($statusOptions as $key => $value) {
        $additionalFilters .= '<option value="' . $key . '">' . $value . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Kategori</label>
            <select wire:model.live="kategoriFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Kategori</option>';
    
    foreach($kategoriOptions as $key => $value) {
        $additionalFilters .= '<option value="' . $key . '">' . $value . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
        <!-- <div class="flex items-end">
            <button wire:click="generatePublicLink" 
                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Generate Link Complaint
            </button>
        </div> -->
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
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'subjek\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Subjek</span>
            ' . ($sortField === 'subjek' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'kategori\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Kategori</span>
            ' . ($sortField === 'kategori' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'status_komplain\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Status</span>
            ' . ($sortField === 'status_komplain' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Response</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'created_at\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Dibuat</span>
            ' . ($sortField === 'created_at' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>';

    // Status badge helper function using model method
    $getStatusBadge = function($status) {
        $complaint = new \App\Models\ComplaintForm(['status_komplain' => $status]);
        $class = $complaint->status_badge_class;
        
        return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $class . '">' . $status . '</span>';
    };

    // Table row function
    $tableRow = function($complaint) use ($getStatusBadge, $kategoriOptions) {
        return '
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                        <span class="text-sm font-medium text-orange-700 dark:text-orange-200">' . 
                            strtoupper(substr($complaint->nama_lengkap, 0, 1)) . 
                        '</span>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">' . $complaint->nama_lengkap . '</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">' . ($complaint->tipe_kamar ? ucfirst(str_replace('_', ' ', $complaint->tipe_kamar)) : '-') . '</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900 dark:text-white">' . $complaint->email . '</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">' . $complaint->nomor_hp . '</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-gray-900 dark:text-white">' . \Illuminate\Support\Str::limit($complaint->subjek, 30) . '</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                ' . ($kategoriOptions[$complaint->kategori] ?? ucfirst($complaint->kategori)) . '
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            ' . $getStatusBadge($complaint->status_komplain) . '
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            ' . ($complaint->admin_response ? 
                '<div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-xs text-green-600">Sudah direspon</span>
                </div>' :
                '<div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs text-gray-500">Belum direspon</span>
                </div>') . '
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . $complaint->created_at->format('d/m/Y H:i') . '
            ' . ($complaint->creator ? '<br><span class="text-xs">oleh: ' . $complaint->creator->name . '</span>' : '') . '
        </td>';
    };

    // Modal content
    $modalContent = '
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" wire:model="nama_lengkap" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('nama_lengkap') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('nama_lengkap') . '</span>' : '') . '
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" wire:model="email" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('email') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('email') . '</span>' : '') . '
            </div>

            <!-- Nomor HP -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                <input type="text" wire:model="nomor_hp" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('nomor_hp') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('nomor_hp') . '</span>' : '') . '
            </div>

            <!-- Tipe Kamar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Kamar <span class="text-red-500">*</span></label>
                <select wire:model="tipe_kamar" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Tipe Kamar</option>';
    
    foreach($tipeKamarOptions as $key => $value) {
        $modalContent .= '<option value="' . $key . '">' . $value . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('tipe_kamar') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('tipe_kamar') . '</span>' : '') . '
            </div>

            <!-- Subjek -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subjek Complaint <span class="text-red-500">*</span></label>
                <input type="text" wire:model="subjek" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('subjek') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('subjek') . '</span>' : '') . '
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select wire:model="kategori" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Kategori</option>';
    
    foreach($kategoriOptions as $key => $value) {
        $modalContent .= '<option value="' . $key . '">' . $value . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('kategori') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('kategori') . '</span>' : '') . '
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                <select wire:model="status_komplain" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Status</option>';
    
    foreach($statusOptions as $key => $value) {
        $modalContent .= '<option value="' . $key . '">' . $value . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('status_komplain') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('status_komplain') . '</span>' : '') . '
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi Complaint <span class="text-red-500">*</span></label>
                <textarea wire:model="deskripsi" rows="4" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                          placeholder="Jelaskan detail complaint atau keluhan..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"></textarea>
                ' . ($this->getErrorBag()->has('deskripsi') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('deskripsi') . '</span>' : '') . '
            </div>

            <!-- Admin Response -->
            <div class="md:col-span-2">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Response Admin</label>
                    ' . ($this->modalMode !== 'view' && $this->recordId ? '
                    <button type="button" wire:click="respondToComplaint" 
                            class="px-3 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded transition duration-200">
                        Kirim Response
                    </button>' : '') . '
                </div>
                <textarea wire:model="admin_response" rows="3" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                          placeholder="Berikan response atau solusi untuk complaint ini..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"></textarea>
                ' . ($this->getErrorBag()->has('admin_response') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('admin_response') . '</span>' : '') . '
            </div>

            <!-- Response Info (hanya tampil jika ada response) -->
            ' . ($this->recordId && $this->responded_at ? '
            <div class="md:col-span-2 bg-green-50 dark:bg-green-900 p-3 rounded-lg">
                <div class="flex items-center mb-2">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-green-800 dark:text-green-200">Sudah Direspon</span>
                </div>
                <div class="text-sm text-green-700 dark:text-green-300">
                    <p><strong>Tanggal:</strong> ' . ($this->responded_at ? date('d/m/Y H:i', strtotime($this->responded_at)) : '-') . '</p>
                    <p><strong>Oleh:</strong> ' . ($this->responded_by ? \App\Models\User::find($this->responded_by)?->name ?? 'Unknown' : '-') . '</p>
                </div>
            </div>' : '') . '

            <!-- Token Info (hanya tampil jika ada) -->
            ' . ($this->recordId && $this->token_used ? '
            <div class="md:col-span-2 bg-blue-50 dark:bg-blue-900 p-3 rounded-lg">
                <div class="flex items-center mb-1">
                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Dibuat via Public Link</span>
                </div>
                <div class="text-xs text-blue-700 dark:text-blue-300 font-mono bg-blue-100 dark:bg-blue-800 p-2 rounded">
                    Token: ' . $this->token_used . '
                </div>
            </div>' : '') . '
        </div>
    </form>';
@endphp

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
    'tableHeaders' => $tableHeaders,
    'tableRow' => $tableRow,
    'modalContent' => $modalContent,
    'records' => $records,
    'selectedRecords' => $selectedRecords,
    'sortField' => $sortField,
    'sortDirection' => $sortDirection,
    'showModal' => $showModal,
    'modalMode' => $modalMode,
    'modalTitle' => $modalTitle,
])

{{-- JavaScript untuk copy to clipboard --}}
@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('copy-to-clipboard', (event) => {
            navigator.clipboard.writeText(event.text).then(() => {
                console.log('Text copied to clipboard');
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        });
    });
</script>
@endpush