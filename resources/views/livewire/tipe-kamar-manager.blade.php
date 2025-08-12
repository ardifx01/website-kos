@php
    // Data untuk base table
    $pageTitle = 'Manajemen Tipe Kamar';
    $pageDescription = 'Kelola data tipe kamar hotel';
    $searchPlaceholder = 'Cari nama tipe kamar, deskripsi, atau harga...';
    $createButtonText = 'Tambah Tipe Kamar';
    $emptyStateTitle = 'Tidak ada tipe kamar';
    $emptyStateDescription = 'Belum ada tipe kamar yang terdaftar atau sesuai dengan pencarian Anda.';
    $tableColspan = 6;

    // No additional filters for now
    $additionalFilters = '';

    // Table headers
    $tableHeaders = '
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'nama\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Nama Tipe</span>
            ' . ($sortField === 'nama' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
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
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Kamar</th>
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
    $tableRow = function($tipeKamar) {
        return '
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">' . $tipeKamar->nama . '</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">ID: ' . $tipeKamar->id . '</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 dark:text-white">
                ' . ($tipeKamar->deskripsi ? 
                    (strlen($tipeKamar->deskripsi) > 100 ? 
                        substr($tipeKamar->deskripsi, 0, 100) . '...' : 
                        $tipeKamar->deskripsi) : 
                    '<span class="text-gray-400 italic">Tidak ada deskripsi</span>') . '
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                ' . $this->formatCurrency($tipeKamar->hargaSewa) . '
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">per malam</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                ' . $tipeKamar->kamars->count() . ' kamar
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            <div>' . $tipeKamar->created_at->format('d/m/Y') . '</div>
            <div class="text-xs">' . ($tipeKamar->creator ? $tipeKamar->creator->name : 'System') . '</div>
        </td>';
    };

    // Modal content
    $modalContent = '
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 gap-4">
            <!-- Nama Tipe Kamar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Tipe Kamar <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="nama" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       placeholder="Contoh: Deluxe, Premium, Standard"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('nama') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('nama') . '</span>' : '') . '
            </div>

            <!-- Harga Sewa -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Harga Sewa (per malam) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">Rp</span>
                    <input type="number" wire:model="hargaSewa" min="0" step="1000" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                           placeholder="500000"
                           class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                </div>
                ' . ($this->getErrorBag()->has('hargaSewa') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('hargaSewa') . '</span>' : '') . '
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Deskripsi Fasilitas
                </label>
                <textarea wire:model="deskripsi" rows="4" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                          placeholder="Deskripsikan fasilitas dan kelebihan tipe kamar ini..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"></textarea>
                ' . ($this->getErrorBag()->has('deskripsi') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('deskripsi') . '</span>' : '') . '
            </div>

            ' . ($this->modalMode === 'view' && $this->recordId ? '
            <!-- Info Tambahan untuk View Mode -->
            <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Tambahan</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Total Kamar:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-white">' . 
                            (isset($this->records) && $this->records->where('id', $this->recordId)->first() ? 
                            $this->records->where('id', $this->recordId)->first()->kamars->count() : 0) . ' kamar</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Harga Format:</span>
                        <span class="ml-2 font-medium text-green-600 dark:text-green-400">' . 
                            ($this->hargaSewa ? $this->formatCurrency($this->hargaSewa) : 'Rp 0') . '</span>
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