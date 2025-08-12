@php
    // Data untuk base table
    $pageTitle = 'Manajemen Kamar';
    $pageDescription = 'Kelola data kamar hotel';
    $searchPlaceholder = 'Cari nomor kamar atau tipe kamar...';
    $createButtonText = 'Tambah Kamar';
    $emptyStateTitle = 'Tidak ada kamar';
    $emptyStateDescription = 'Belum ada kamar yang terdaftar atau sesuai dengan pencarian Anda.';
    $tableColspan = 7;

    // Additional filters content
    $additionalFilters = <<<HTML
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Status</label>
            <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Status</option>
HTML;
    foreach($statusOptions as $value => $label) {
        $additionalFilters .= "<option value=\"{$value}\">{$label}</option>";
    }

    $additionalFilters .= <<<HTML
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Kamar</label>
            <select wire:model.live="tipeKamarFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Tipe</option>
HTML;
    foreach($tipeKamars as $tipeKamar) {
        $additionalFilters .= "<option value=\"{$tipeKamar->id}\">{$tipeKamar->nama}</option>";
    }

    $additionalFilters .= <<<HTML
            </select>
        </div>
    </div>
HTML;

    // Table headers
    $iconAsc = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>';
    $iconDesc = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>';

    $tableHeaders = <<<HTML
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy('nomorKamar')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>No. Kamar</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {$iconAsc}
            </svg>
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe Kamar</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Sewa</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy('status')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Status</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {$iconDesc}
            </svg>
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Dibuat</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {$iconAsc}
            </svg>
        </button>
    </th>
HTML;

    // Table row function
    $tableRow = function($kamar) use ($statusOptions) {
        $statusBadgeClass = match($kamar->status ?? '') {
            'tersedia' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'terisi' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'maintenance' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'renovasi' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };

        $tipeKamarNama = $kamar->tipeKamar->nama ?? 'Tidak ada tipe';
        $tipeKamarDeskripsi = strlen($kamar->tipeKamar->deskripsi ?? '') > 50
            ? substr($kamar->tipeKamar->deskripsi, 0, 50) . '...'
            : ($kamar->tipeKamar->deskripsi ?? 'Tidak ada deskripsi');
        $hargaSewa = 'Rp ' . number_format($kamar->tipeKamar->hargaSewa ?? 0, 0, ',', '.');
        $statusLabel = $statusOptions[$kamar->status] ?? $kamar->status;
        $creatorName = $kamar->creator->name ?? 'System';

        return <<<HTML
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{$kamar->nomorKamar}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900 dark:text-white">{$tipeKamarNama}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{$tipeKamarDeskripsi}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-semibold text-green-600 dark:text-green-400">{$hargaSewa}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">per bulan</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {$statusBadgeClass}">{$statusLabel}</span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            <div>{$kamar->created_at->format('d/m/Y')}</div>
            <div class="text-xs">{$creatorName}</div>
        </td>
HTML;
    };

    // Modal content
    $modalContent = <<<HTML
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nomor Kamar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nomor Kamar <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="nomorKamar" placeholder="Contoh: 101, A-202"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Tipe Kamar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tipe Kamar <span class="text-red-500">*</span>
                </label>
                <select wire:model="tipe_kamar_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Tipe Kamar</option>
HTML;
    foreach($tipeKamars as $tipeKamar) {
        $hargaFormatted = 'Rp ' . number_format($tipeKamar->hargaSewa, 0, ',', '.');
        $modalContent .= "<option value=\"{$tipeKamar->id}\">{$tipeKamar->nama} - {$hargaFormatted}</option>";
    }

    $modalContent .= <<<HTML
                </select>
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Status <span class="text-red-500">*</span>
                </label>
                <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Status</option>
HTML;
    foreach($statusOptions as $value => $label) {
        $modalContent .= "<option value=\"{$value}\">{$label}</option>";
    }

    $modalContent .= <<<HTML
                </select>
            </div>

            <!-- Info Tambahan untuk View Mode -->
HTML;

    if ($this->modalMode === 'view' && $this->recordId) {
        $selectedTipeKamar = $tipeKamars->firstWhere('id', $this->tipe_kamar_id);
        $hargaFormatted = $selectedTipeKamar ? 'Rp ' . number_format($selectedTipeKamar->hargaSewa, 0, ',', '.') : 'Rp 0';
        $deskripsi = $selectedTipeKamar->deskripsi ?? 'Tidak ada deskripsi';

        $modalContent .= <<<HTML
            <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-600 pt-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Detail</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">ID Kamar:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-white">{$this->recordId}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Harga Sewa:</span>
                        <span class="ml-2 font-medium text-green-600 dark:text-green-400">{$hargaFormatted}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-gray-500 dark:text-gray-400">Deskripsi Tipe:</span>
                        <div class="mt-1 text-gray-900 dark:text-white">{$deskripsi}</div>
                    </div>
                </div>
            </div>
HTML;
    }

    $modalContent .= <<<HTML
        </div>
    </form>
HTML;
@endphp

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