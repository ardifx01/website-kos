@php
    // Data untuk base table
    $pageTitle = 'Manajemen User';
    $pageDescription = 'Kelola data user sistem';
    $searchPlaceholder = 'Cari nama, email, phone, atau role...';
    $createButtonText = 'Tambah User';
    $emptyStateTitle = 'Tidak ada user';
    $emptyStateDescription = 'Belum ada user yang terdaftar atau sesuai dengan pencarian Anda.';
    $tableColspan = 7;

    // Additional filters content
    $additionalFilters = '
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Role</label>
        <select wire:model.live="roleFilter" 
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            <option value="">Semua Role</option>';
    
    foreach($roles as $role) {
        $additionalFilters .= '<option value="' . $role->id . '">' . $role->name . '</option>';
    }
    
    $additionalFilters .= '
        </select>
    </div>';

    // Table headers
    $tableHeaders = '
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'name\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Nama</span>
            ' . ($sortField === 'name' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'email\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Email</span>
            ' . ($sortField === 'email' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
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
    $tableRow = function($user) {
        return '
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    ' . ($user->photo ? 
                        '<img class="h-10 w-10 rounded-full object-cover" src="' . Storage::url($user->photo) . '" alt="' . $user->name . '">' :
                        '<div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">' . $user->initials() . '</span>
                        </div>') . '
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">' . $user->name . '</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">' . ($user->phone ?? '-') . '</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">' . $user->email . '</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                ' . ($user->role->name ?? 'No Role') . '
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            ' . ($user->last_login_at ? 
                '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Aktif</span>' :
                '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">Belum Login</span>') . '
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . $user->created_at->format('d/m/Y') . '
        </td>';
    };

    // Modal content
    $modalContent = '
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Photo -->
            <div class="md:col-span-2 flex justify-center">
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-600 mb-4">
                        ' . ($this->photoPreview ? 
                            '<img src="' . $this->photoPreview . '" alt="Preview" class="w-full h-full object-cover">' : 
                            ($this->photo ? 
                                '<img src="' . $this->photo->temporaryUrl() . '" alt="Preview" class="w-full h-full object-cover">' :
                                '<div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>')) . '
                    </div>
                    ' . ($this->modalMode !== 'view' ? '
                    <input type="file" wire:model="photo" accept="image/*" class="text-sm text-gray-500 dark:text-gray-400">
                    ' . ($this->getErrorBag()->has('photo') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('photo') . '</span>' : '') : '') . '
                </div>
            </div>

            <!-- Name -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('name') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('name') . '</span>' : '') . '
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" wire:model="email" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('email') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('email') . '</span>' : '') . '
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role <span class="text-red-500">*</span></label>
                <select wire:model="role_id" ' . ($this->modalMode === 'view' ? 'disabled' : '') . '
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                    <option value="">Pilih Role</option>';
    
    foreach($roles as $role) {
        $modalContent .= '<option value="' . $role->id . '">' . $role->name . '</option>';
    }
    
    $modalContent .= '
                </select>
                ' . ($this->getErrorBag()->has('role_id') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('role_id') . '</span>' : '') . '
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                <input type="text" wire:model="phone" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('phone') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('phone') . '</span>' : '') . '
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                <textarea wire:model="address" rows="3" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"></textarea>
                ' . ($this->getErrorBag()->has('address') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('address') . '</span>' : '') . '
            </div>

            ' . ($this->modalMode !== 'view' ? '
            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Password 
                    ' . ($this->modalMode === 'create' ? 
                        '<span class="text-red-500">*</span>' : 
                        '<span class="text-xs text-gray-500">(kosongkan jika tidak ingin mengubah)</span>') . '
                </label>
                <input type="password" wire:model="password"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                ' . ($this->getErrorBag()->has('password') ? '<span class="text-red-500 text-xs">' . $this->getErrorBag()->first('password') . '</span>' : '') . '
            </div>

            <!-- Password Confirmation -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                <input type="password" wire:model="password_confirmation"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
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