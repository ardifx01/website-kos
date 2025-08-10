@php
    // Data untuk base table
    $pageTitle = 'Manajemen Fasilitas Kamar';
    $pageDescription = 'Kelola data fasilitas kamar kost';
    $searchPlaceholder = 'Cari nama fasilitas, kondisi, atau nomor kamar...';
    $createButtonText = 'Tambah Fasilitas';
    $emptyStateTitle = 'Tidak ada fasilitas';
    $emptyStateDescription = 'Belum ada fasilitas kamar yang terdaftar atau sesuai dengan pencarian Anda.';
    $tableColspan = 6;

    // Additional filters content
    $additionalFilters = '
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Kamar</label>
            <select wire:model.live="kamarFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Kamar</option>';
    
    foreach($kamarOptions as $key => $label) {
        $additionalFilters .= '<option value="' . $key . '">' . $label . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Kondisi</label>
            <select wire:model.live="kondisiFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Kondisi</option>';
    
    foreach($kondisiOptions as $key => $label) {
        $additionalFilters .= '<option value="' . $key . '">' . $label . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
    </div>';

    // Table headers
    $tableHeaders = '
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'namaFasilitas\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Nama Fasilitas</span>
            ' . ($sortField === 'namaFasilitas' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kamar</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'kondisi\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Kondisi</span>
            ' . ($sortField === 'kondisi' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
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
    $tableRow = function($fasilitas) {
        return '
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center">
                        <span class="text-lg text-white">' . $this->getKondisiIcon($fasilitas->kondisi) . '</span>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">' . $fasilitas->namaFasilitas . '</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Fasilitas Kamar</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-8 w-8">
                    <div class="h-8 w-8 rounded bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center">
                        <span class="text-xs font-bold text-white">' . substr($fasilitas->kamar->nomorKamar ?? '', 0, 2) . '</span>
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">' . ($fasilitas->kamar->nomorKamar ?? 'N/A') . '</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">' . ($fasilitas->kamar->tipeKamar ?? '') . '</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $this->getKondisiBadgeClass($fasilitas->kondisi) . '">
                ' . ($this->kondisiOptions[$fasilitas->kondisi] ?? $fasilitas->kondisi) . '
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . $fasilitas->created_at->format('d/m/Y') . '
        </td>';
    };

    // Modal content
    $modalContent = '
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Kamar -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kamar <span class="text-red-500">*</span></label>
                <select wire:model="idKamar" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Kamar</option>';
    
    foreach($kamarOptions as $key => $label) {
        $modalContent .= '<option value="' . $key . '">' . $label . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('idKamar') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('idKamar') . '</span>' : '') . '
            </div>

            <!-- Nama Fasilitas -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Fasilitas <span class="text-red-500">*</span></label>
                <input type="text" wire:model="namaFasilitas" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"
                       placeholder="Contoh: Kasur, Lemari, AC, Meja Belajar">
                ' . ($this->getErrorBag()->has('namaFasilitas') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('namaFasilitas') . '</span>' : '') . '
            </div>

            <!-- Kondisi -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kondisi <span class="text-red-500">*</span></label>
                <select wire:model="kondisi" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Kondisi</option>';
    
    foreach($kondisiOptions as $key => $label) {
        $modalContent .= '<option value="' . $key . '">' . $label . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('kondisi') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('kondisi') . '</span>' : '') . '
            </div>

            <!-- Info Tambahan untuk View Mode -->
            ' . ($this->modalMode === 'view' && isset($this->recordId) && $this->recordId ? '
            <div class="md:col-span-2">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Tambahan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Dibuat:</span>
                            <span class="text-gray-900 dark:text-white ml-2">' . (isset($records) && $records->firstWhere('idFasilitas', $this->recordId) ? $records->firstWhere('idFasilitas', $this->recordId)->created_at->format('d/m/Y H:i') : '-') . '</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Terakhir Diupdate:</span>
                            <span class="text-gray-900 dark:text-white ml-2">' . (isset($records) && $records->firstWhere('idFasilitas', $this->recordId) ? $records->firstWhere('idFasilitas', $this->recordId)->updated_at->format('d/m/Y H:i') : '-') . '</span>
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