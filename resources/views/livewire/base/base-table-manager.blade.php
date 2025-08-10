<div class="p-6">
    {{-- SESSION FLASH MESSAGE - Tampil otomatis setelah reload --}}
    @if(session()->has('alert'))
        <div class="mb-6 p-4 rounded-lg border-l-4 {{ 
            session('alert.type') === 'success' ? 'bg-green-50 border-green-500 text-green-700' : (
            session('alert.type') === 'error' ? 'bg-red-50 border-red-500 text-red-700' : (
            session('alert.type') === 'warning' ? 'bg-yellow-50 border-yellow-500 text-yellow-700' : 
            'bg-blue-50 border-blue-500 text-blue-700'))
        }}" id="sessionAlert">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if(session('alert.type') === 'success')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif(session('alert.type') === 'error')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif(session('alert.type') === 'warning')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">{{ session('alert.message') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="closeSessionAlert()" class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- LIVEWIRE VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-700 mb-2">Terdapat kesalahan:</h3>
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pageTitle ?? 'Data Management' }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $pageDescription ?? 'Kelola data dengan mudah' }}</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <button wire:click="create" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ $createButtonText ?? 'Tambah Data' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pencarian</label>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" 
                           type="text" 
                           placeholder="{{ $searchPlaceholder ?? 'Cari data...' }}" 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Additional Filters Slot -->
            @if(isset($additionalFilters))
                {!! $additionalFilters !!}
            @endif

            <!-- Per Page -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tampilkan</label>
                <select wire:model.live="perPage" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if(count($selectedRecords) > 0)
        <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-800 dark:text-blue-200">
                    {{ count($selectedRecords) }} data dipilih
                </span>
                <div class="flex space-x-2">
                    @if(isset($bulkActions))
                        {!! $bulkActions !!}
                    @endif
                    <button wire:click="confirmBulkDelete" 
                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded-md transition-colors duration-200">
                        Hapus Terpilih
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" 
                                   wire:model.live="selectAll"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        {!! $tableHeaders !!}
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($records as $record)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" 
                                       wire:model.live="selectedRecords" 
                                       value="{{ $record->id }}"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            {!! $tableRow($record) !!}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="view({{ $record->id }})" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                                            title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="edit({{ $record->id }})" 
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200"
                                            title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if(!$this->cannotDelete($record))
                                        <button wire:click="confirmDelete({{ $record->id }})" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200"
                                                title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed transition-colors duration-200" 
                                              title="Tidak dapat menghapus data ini">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $tableColspan ?? 7 }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                <div class="flex flex-col items-center justify-center py-12">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium mb-2">{{ $emptyStateTitle ?? 'Tidak ada data' }}</p>
                                    <p class="text-sm">{{ $emptyStateDescription ?? 'Belum ada data yang tersedia atau sesuai dengan pencarian Anda.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($records->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $records->links() }}
            </div>
        @endif
    </div>

    <!-- Main Modal (Create/Edit/View) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 9999;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-opacity-75 transition-opacity" 
                     wire:click="closeModal"
                     style="z-index: 9998;"></div>
                
                <!-- Modal dialog -->
                <div class="relative inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                     style="z-index: 9999;">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $modalTitle }}</h3>
                            <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Content -->
                        @if(isset($modalContent))
                            {!! $modalContent !!}
                        @endif

                        <!-- Modal Footer -->
                        <div class="mt-6 flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                {{ $modalMode === 'view' ? 'Tutup' : 'Batal' }}
                            </button>
                            @if($modalMode !== 'view')
                                <button type="button" wire:click="save"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    {{ $modalMode === 'create' ? 'Simpan' : 'Update' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modern Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-[9999] overflow-y-auto" style="z-index: 9999 !important;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-opacity-75 backdrop-blur-sm transition-opacity" 
                     wire:click="cancelDelete"
                     style="z-index: 9998;"></div>
                
                <!-- Modal dialog -->
                <div class="relative inline-block align-middle bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                     style="z-index: 9999;">
                     
                    <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                        <!-- Warning icon -->
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                            <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                {{ $deleteTitle }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                                {{ $deleteMessage }}
                            </p>
                            
                            @if(!empty($deleteDetails))
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 mb-6 text-left">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Data yang akan dihapus:</p>
                                            <p class="text-sm text-gray-900 dark:text-white font-semibold">{{ $deleteDetails }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Warning message -->
                            <div class="flex items-center justify-center space-x-2 text-xs text-red-600 dark:text-red-400 mb-6 bg-red-50 dark:bg-red-900/20 rounded-lg p-3">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium">Tindakan ini tidak dapat dibatalkan</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal footer -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex space-x-3 justify-end">
                        <button type="button" wire:click="cancelDelete"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200">
                            Batal
                        </button>
                        <button type="button" wire:click="executeDelete"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200">
                            {{ $isBulkDelete ? 'Ya, Hapus Semua' : 'Ya, Hapus' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-opacity-50 backdrop-blur-sm" style="z-index: 10000;">
        <div class="bg-white rounded-2xl p-8 shadow-2xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-center space-x-4">
                <div class="relative">
                    <!-- Main spinning circle -->
                    <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    <!-- Ping effect -->
                    <div class="absolute inset-0 animate-ping">
                        <svg class="h-8 w-8 text-blue-400 opacity-20" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        </svg>
                    </div>
                </div>
                
                <!-- Loading text -->
                <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Memproses...</span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes modal-enter {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Pastikan z-index tertinggi untuk modal delete */
    .z-\\[9999\\] {
        z-index: 9999 !important;
    }

    .z-\\[10000\\] {
        z-index: 10000 !important;
    }

    .z-\\[10001\\] {
        z-index: 10001 !important;
    }

    .animate-modal-enter {
        animation: modal-enter 0.3s ease-out forwards;
    }

    /* Custom hover effects */
    .group:hover .group-hover\:animate-wiggle {
        animation: wiggle 0.3s ease-in-out;
    }

    @keyframes wiggle {
        0%, 7% { transform: rotateZ(0); }
        15% { transform: rotateZ(-15deg); }
        20% { transform: rotateZ(10deg); }
        25% { transform: rotateZ(-10deg); }
        30% { transform: rotateZ(6deg); }
        35% { transform: rotateZ(-4deg); }
        40%, 100% { transform: rotateZ(0); }
    }

    /* Smooth transitions for all interactive elements */
    button, input[type="checkbox"], select {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Enhanced focus styles */
    button:focus-visible, input:focus-visible, select:focus-visible {
        outline: 2px solid theme(colors.blue.500);
        outline-offset: 2px;
    }

    /* Dark mode improvements */
    @media (prefers-color-scheme: dark) {
        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
        }
    }

    /* PERBAIKAN: Custom styles for perfect loading centering */
    .loading-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 10000 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: blur(4px) !important;
    }

    /* Animation for the spinning effect */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    .animate-ping {
        animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    /* PERBAIKAN: Pastikan loading content berada tepat di tengah */
    [wire\:loading] {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 10000 !important;
    }

    /* Loading container positioning */
    [wire\:loading] > div {
        position: relative !important;
        transform: translate(0, 0) !important;
        margin: auto !important;
    }

    .ripple {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Enhanced script with better error handling and modern features
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Livewire is available
        if (typeof Livewire !== 'undefined') {
            // Modern alert system with better UX
            Livewire.on('alert', (data) => {
                console.log('Alert received:', data);
                
                const alertData = Array.isArray(data) ? data[0] : data;
                const { type, message } = alertData;
                
                showModernAlert(type, message);
            });
        } else {
            console.warn('Livewire not found. Make sure Livewire scripts are loaded.');
        }
        
        // Fallback for older Livewire versions
        if (typeof window.livewire !== 'undefined') {
            window.livewire.on('alert', function (data) {
                console.log('Alert received (fallback):', data);
                const alertData = Array.isArray(data) ? data[0] : data;
                const { type, message } = alertData;
                showModernAlert(type, message);
            });
        }
    });
    
    // Modern alert function with enhanced styling
    function showModernAlert(type, message) {
        // Remove existing alerts
        document.querySelectorAll('[data-alert="true"]').forEach(el => el.remove());
        
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.setAttribute('data-alert', 'true');
        alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-2xl transition-all duration-500 transform translate-x-full max-w-sm border-l-4`;
        
        // Type-specific styling
        const typeStyles = {
            success: {
                bg: 'bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20',
                border: 'border-green-500',
                text: 'text-green-800 dark:text-green-200',
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>`
            },
            error: {
                bg: 'bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20',
                border: 'border-red-500',
                text: 'text-red-800 dark:text-red-200',
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>`
            },
            warning: {
                bg: 'bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20',
                border: 'border-yellow-500',
                text: 'text-yellow-800 dark:text-yellow-200',
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                      </svg>`
            },
            info: {
                bg: 'bg-gradient-to-r from-blue-50 to-sky-50 dark:from-blue-900/20 dark:to-sky-900/20',
                border: 'border-blue-500',
                text: 'text-blue-800 dark:text-blue-200',
                icon: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>`
            }
        };
        
        const style = typeStyles[type] || typeStyles.info;
        alertDiv.className += ` ${style.bg} ${style.border} ${style.text}`;
        
        alertDiv.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0 ${style.text}">
                    ${style.icon}
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="removeAlert(this.parentElement.parentElement)" 
                            class="inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-lg p-1 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Slide in animation
        requestAnimationFrame(() => {
            alertDiv.classList.remove('translate-x-full');
            alertDiv.classList.add('translate-x-0');
        });
        
        // Auto remove after 6 seconds
        setTimeout(() => removeAlert(alertDiv), 6000);
    }
    
    // Enhanced alert removal function
    function removeAlert(alertDiv) {
        if (alertDiv && alertDiv.parentNode) {
            alertDiv.classList.remove('translate-x-0');
            alertDiv.classList.add('translate-x-full', 'opacity-0');
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 500);
        }
    }
    
    // Keyboard shortcuts for better UX
    document.addEventListener('keydown', function(e) {
        // Close delete modal with Escape key
        if (e.key === 'Escape') {
            const deleteModal = document.querySelector('[wire\\:click="cancelDelete"]');
            if (deleteModal) {
                deleteModal.click();
            }
        }
    });
    
    // Enhanced table interactions
    document.addEventListener('click', function(e) {
        // Add ripple effect to buttons
        if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
            createRipple(e);
        }
    });
    
    function createRipple(event) {
        const button = event.target.tagName === 'BUTTON' ? event.target : event.target.closest('button');
        if (!button) return;
        
        const circle = document.createElement('span');
        const diameter = Math.max(button.clientWidth, button.clientHeight);
        const radius = diameter / 2;
        
        const rect = button.getBoundingClientRect();
        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${event.clientX - rect.left - radius}px`;
        circle.style.top = `${event.clientY - rect.top - radius}px`;
        circle.classList.add('ripple');
        
        const ripple = button.querySelector('.ripple');
        if (ripple) {
            ripple.remove();
        }
        
        button.appendChild(circle);
        
        setTimeout(() => {
            circle.remove();
        }, 600);
    }
</script>

<style>
    
</style>
@endpush