@php
    // Data untuk base table
    $pageTitle = 'Manajemen Role';
    $pageDescription = 'Kelola data role sistem';
    $searchPlaceholder = 'Cari nama atau deskripsi role...';
    $createButtonText = 'Tambah Role';
    $emptyStateTitle = 'Tidak ada role';
    $emptyStateDescription = 'Belum ada role yang terdaftar atau sesuai dengan pencarian Anda.';
    $tableColspan = 5;

    // Additional filters content (empty for role)
    $additionalFilters = '';

    // Table headers
    $tableHeaders = '
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'name\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Nama Role</span>
            ' . ($sortField === 'name' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah User</th>
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
    $tableRow = function($role) {
        return '
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">' . $role->name . '</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        ' . (strtolower($role->name) === 'super-admin' || strtolower($role->name) === 'super admin' ? 
                            '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">System Role</span>' : 
                            'Custom Role') . '
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate" title="' . ($role->description ?? '-') . '">
                ' . ($role->description ?? '-') . '
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . 
                    ($role->users_count > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200') . '">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    ' . $role->users_count . ' user' . ($role->users_count != 1 ? 's' : '') . '
                </span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            <div>
                <div>' . $role->created_at->format('d/m/Y') . '</div>
                <div class="text-xs text-gray-400 dark:text-gray-500">' . $role->created_at->format('H:i') . '</div>
            </div>
        </td>';
    };

    // Modal content
    $modalContent = '
    <form wire:submit.prevent="save">
        <div class="space-y-4">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Role <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="name" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                       placeholder="Masukkan nama role..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '">
                ' . ($this->getErrorBag()->has('name') ? '<span class="text-red-500 text-xs mt-1 block">' . $this->getErrorBag()->first('name') . '</span>' : '') . '
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Deskripsi
                </label>
                <textarea wire:model="description" rows="4" ' . ($this->modalMode === 'view' ? 'readonly' : '') . '
                          placeholder="Masukkan deskripsi role..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white ' . ($this->modalMode === 'view' ? 'bg-gray-50 dark:bg-gray-600' : '') . '"></textarea>
                ' . ($this->getErrorBag()->has('description') ? '<span class="text-red-500 text-xs mt-1 block">' . $this->getErrorBag()->first('description') . '</span>' : '') . '
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jelaskan fungsi dan tanggung jawab role ini</p>
            </div>

            ' . ($this->modalMode === 'view' && $this->recordId ? '
            <!-- Additional Info for View Mode -->
            <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Tambahan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Jumlah User:</span>
                        <span class="ml-2 font-medium text-gray-900 dark:text-white">' . ($this->getModelClass()::withCount('users')->find($this->recordId)->users_count ?? 0) . ' user</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Status:</span>
                        <span class="ml-2">
                            ' . (strtolower($this->name) === 'super-admin' || strtolower($this->name) === 'super admin' ? 
                                '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">System Role</span>' : 
                                '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Custom Role</span>') . '
                        </span>
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