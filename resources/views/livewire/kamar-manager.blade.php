@php
    // Data untuk base table
    $pageTitle = 'Manajemen Kamar';
    $pageDescription = 'Kelola data kamar kost';
    $searchPlaceholder = 'Cari nomor kamar, tipe, harga, atau status...';
    $createButtonText = 'Tambah Kamar';
    $emptyStateTitle = 'Tidak ada kamar';
    $emptyStateDescription = 'Belum ada kamar yang terdaftar atau sesuai dengan pencarian Anda.';
    $tableColspan = 6;

    // Additional filters content
    $additionalFilters = '
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Status</label>
            <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Status</option>';
    
    foreach($statusOptions as $key => $label) {
        $additionalFilters .= '<option value="' . $key . '">' . $label . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Tipe</label>
            <select wire:model.live="tipeFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Tipe</option>';
    
    foreach($tipeOptions as $key => $label) {
        $additionalFilters .= '<option value="' . $key . '">' . $label . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
    </div>';

    // Table headers
    $tableHeaders = '
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'nomorKamar\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Nomor Kamar</span>
            ' . ($sortField === 'nomorKamar' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'tipeKamar\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Tipe Kamar</span>
            ' . ($sortField === 'tipeKamar' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'hargaSewa\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Harga Sewa</span>
            ' . ($sortField === 'hargaSewa' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
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

    // Table row function
    $tableRow = function($kamar) {
        return '
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-sm font-bold text-white">' . substr($kamar->nomorKamar, 0, 3) . '</span>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">' . $kamar->nomorKamar . '</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Kamar ' . $kamar->nomorKamar . '</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $this->getTipeBadgeClass($kamar->tipeKamar) . '">
                ' . ($this->tipeOptions[$kamar->tipeKamar] ?? $kamar->tipeKamar) . '
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
            ' . $this->formatHarga($kamar->hargaSewa) . '
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $this->getStatusBadgeClass($kamar->status) . '">
                ' . ($this->statusOptions[$kamar->status] ?? $kamar->status) . '
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . $kamar->created_at->format('d/m/Y') . '
        </td>';
    };

    // Modal content
    $modalContent = '
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nomor Kamar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Kamar <span class="text-red-500">*</span></label>
                <input type="text" wire:model="nomorKamar" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"
                       placeholder="Contoh: A001">
                ' . ($this->getErrorBag()->has('nomorKamar') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('nomorKamar') . '</span>' : '') . '
            </div>

            <!-- Tipe Kamar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Kamar <span class="text-red-500">*</span></label>
                <select wire:model="tipeKamar" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Tipe Kamar</option>';
    
    foreach($tipeOptions as $key => $label) {
        $modalContent .= '<option value="' . $key . '">' . $label . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('tipeKamar') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('tipeKamar') . '</span>' : '') . '
            </div>

            <!-- Harga Sewa -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Sewa <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" wire:model="hargaSewa" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                           class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"
                           placeholder="0" min="0">
                </div>
                ' . ($this->getErrorBag()->has('hargaSewa') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('hargaSewa') . '</span>' : '') . '
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                <select wire:model="status" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Status</option>';
    
    foreach($statusOptions as $key => $label) {
        $modalContent .= '<option value="' . $key . '">' . $label . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('status') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('status') . '</span>' : '') . '
            </div>

            <!-- Info Tambahan untuk View Mode -->
            ' . ($this->modalMode === 'view' && isset($this->recordId) && $this->recordId ? '
            <div class="md:col-span-2">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Tambahan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Dibuat:</span>
                            <span class="text-gray-900 dark:text-white ml-2">' . (isset($records) && $records->firstWhere('idKamar', $this->recordId) ? $records->firstWhere('idKamar', $this->recordId)->created_at->format('d/m/Y H:i') : '-') . '</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Terakhir Diupdate:</span>
                            <span class="text-gray-900 dark:text-white ml-2">' . (isset($records) && $records->firstWhere('idKamar', $this->recordId) ? $records->firstWhere('idKamar', $this->recordId)->updated_at->format('d/m/Y H:i') : '-') . '</span>
                        </div>
                    </div>
                </div>
            </div>
            ' : '') . '
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