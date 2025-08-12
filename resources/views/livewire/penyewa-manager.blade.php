@php
    // Data untuk base table
    $pageTitle = 'Manajemen Penyewa';
    $pageDescription = 'Kelola data penyewa kost';
    $searchPlaceholder = 'Cari nama, email, nomor HP, atau pekerjaan...';
    $createButtonText = 'Tambah Penyewa';
    $emptyStateTitle = 'Tidak ada penyewa';
    $emptyStateDescription = 'Belum ada penyewa yang terdaftar atau sesuai dengan pencarian Anda.';
    $tableColspan = 8;

    // Additional filters content
    $additionalFilters = '
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Status</label>
            <select wire:model.live="statusFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Status</option>';
    
    foreach($statusSewaOptions as $value => $label) {
        $additionalFilters .= '<option value="' . $value . '">' . $label . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Tipe Kamar</label>
            <select wire:model.live="tipeKamarFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Tipe</option>';
    
    foreach($tipeKamarOptions as $value => $label) {
        $additionalFilters .= '<option value="' . $value . '">' . $label . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
    </div>';

    // Table headers
    $tableHeaders = '
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'nama_lengkap\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Nama Lengkap</span>
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
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe Kamar</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'tanggal_masuk\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Tanggal Masuk</span>
            ' . ($sortField === 'tanggal_masuk' ? '
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
    $tableRow = function($penyewa) {
        // Status badge color mapping
        $statusColors = [
            'aktif' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'tidak_aktif' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'keluar' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
        ];
        
        // Tipe kamar badge color
        $tipeKamarColors = [
            'deluxe' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'premium' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'triple' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            'family' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200'
        ];
        
        return '
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">' . 
                            strtoupper(substr($penyewa->nama_lengkap, 0, 2)) . '
                        </span>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">' . $penyewa->nama_lengkap . '</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">' . $penyewa->pekerjaan . '</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900 dark:text-white">' . $penyewa->email . '</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">' . $penyewa->nomor_hp . '</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . 
                ($tipeKamarColors[$penyewa->tipe_kamar] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200') . '">
                ' . ucfirst($penyewa->tipe_kamar) . '
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . 
                ($statusColors[$penyewa->status_sewa] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200') . '">
                ' . ucfirst(str_replace('_', ' ', $penyewa->status_sewa)) . '
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . \Carbon\Carbon::parse($penyewa->tanggal_masuk)->format('d/m/Y') . '
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . $penyewa->created_at->format('d/m/Y') . '
        </td>';
    };

    // Modal content
    $modalContent = '
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Nama Lengkap -->
            <div class="md:col-span-2">
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

            <!-- Jenis Kelamin -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select wire:model="jenis_kelamin" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Jenis Kelamin</option>';
    
    foreach($jenisKelaminOptions as $value => $label) {
        $modalContent .= '<option value="' . $value . '">' . $label . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('jenis_kelamin') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('jenis_kelamin') . '</span>' : '') . '
            </div>

            <!-- Pekerjaan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                <input type="text" wire:model="pekerjaan" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('pekerjaan') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('pekerjaan') . '</span>' : '') . '
            </div>

            <!-- Alamat KTP -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat KTP <span class="text-red-500">*</span></label>
                <textarea wire:model="alamat_ktp" rows="3" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"></textarea>
                ' . ($this->getErrorBag()->has('alamat_ktp') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('alamat_ktp') . '</span>' : '') . '
            </div>

            <!-- Alamat Domisili -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Domisili</label>
                <textarea wire:model="alamat_domisili" rows="3" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"></textarea>
                ' . ($this->getErrorBag()->has('alamat_domisili') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('alamat_domisili') . '</span>' : '') . '
            </div>

            <!-- Tipe Kamar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Kamar <span class="text-red-500">*</span></label>
                <select wire:model="tipe_kamar" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Tipe Kamar</option>';
    
    foreach($tipeKamarOptions as $value => $label) {
        $modalContent .= '<option value="' . $value . '">' . $label . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('tipe_kamar') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('tipe_kamar') . '</span>' : '') . '
            </div>

            <!-- Jumlah Orang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Orang <span class="text-red-500">*</span></label>
                <input type="number" wire:model="jumlah_orang" min="1" max="10" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('jumlah_orang') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('jumlah_orang') . '</span>' : '') . '
            </div>

            <!-- Tanggal Masuk -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Masuk <span class="text-red-500">*</span></label>
                <input type="date" wire:model="tanggal_masuk" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('tanggal_masuk') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('tanggal_masuk') . '</span>' : '') . '
            </div>

            <!-- Status Sewa -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Sewa <span class="text-red-500">*</span></label>
                <select wire:model="status_sewa" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Status</option>';
    
    foreach($statusSewaOptions as $value => $label) {
        $modalContent .= '<option value="' . $value . '">' . $label . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('status_sewa') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('status_sewa') . '</span>' : '') . '
            </div>

            <!-- Booking Form (Optional) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Booking Form (Opsional)</label>
                <select wire:model="booking_form_id" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Booking Form</option>';
    
    foreach($bookingForms as $booking) {
        $modalContent .= '<option value="' . $booking->id . '">' . $booking->nama_lengkap . ' (' . $booking->created_at->format('d/m/Y') . ')</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('booking_form_id') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('booking_form_id') . '</span>' : '') . '
            </div>

            <!-- Catatan -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                <textarea wire:model="catatan" rows="3" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"
                          placeholder="Catatan tambahan mengenai penyewa..."></textarea>
                ' . ($this->getErrorBag()->has('catatan') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('catatan') . '</span>' : '') . '
            </div>

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