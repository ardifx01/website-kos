@php
    // Data untuk base table
    $pageTitle = 'Log Aktivitas';
    $pageDescription = 'Monitor aktivitas pengguna sistem';
    $searchPlaceholder = 'Cari event, tabel, IP, atau nama user...';
    $createButtonText = '';
    $emptyStateTitle = 'Tidak ada log aktivitas';
    $emptyStateDescription = 'Belum ada aktivitas yang tercatat atau sesuai dengan pencarian Anda.';
    $tableColspan = 7;

    // Additional filters content
    $additionalFilters = '
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Event</label>
            <select wire:model.live="eventFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Event</option>';
    
    foreach($events as $event) {
        $additionalFilters .= '<option value="' . $event . '">' . ucfirst($event) . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Tabel</label>
            <select wire:model.live="tableFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua Tabel</option>';
    
    foreach($tables as $table) {
        $additionalFilters .= '<option value="' . $table . '">' . ucfirst(str_replace('_', ' ', $table)) . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter User</label>
            <select wire:model.live="userFilter" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <option value="">Semua User</option>';
    
    foreach($users as $user) {
        $additionalFilters .= '<option value="' . $user->id . '">' . $user->name . '</option>';
    }
    
    $additionalFilters .= '
            </select>
        </div>
    </div>';

    // Table headers
    $tableHeaders = '
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'created_date\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Waktu</span>
            ' . ($sortField === 'created_date' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'event\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Event</span>
            ' . ($sortField === 'event' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
        <button wire:click="sortBy(\'table_name\')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-100">
            <span>Tabel</span>
            ' . ($sortField === 'table_name' ? '
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ' . ($sortDirection === 'asc' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>') . '
                </svg>
            ' : '') . '
        </button>
    </th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Record ID</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">IP Address</th>';

    // Table row function
    $tableRow = function($log) {
        $eventColors = [
            'created' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'updated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'login' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            'logout' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        ];
        
        $eventColor = $eventColors[$log->event] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        
        return '
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
            ' . $log->created_date->format('d/m/Y H:i:s') . '
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-8 w-8">
                    <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                            ' . ($log->user ? strtoupper(substr($log->user->name, 0, 2)) : 'SY') . '
                        </span>
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                        ' . ($log->user ? $log->user->name : 'System') . '
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ' . $eventColor . '">
                ' . ucfirst($log->event) . '
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
            ' . ucfirst(str_replace('_', ' ', $log->table_name)) . '
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . ($log->record_id ?? '-') . '
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            ' . $log->ip_address . '
        </td>';
    };

    // Modal content for viewing log details
    $modalContent = '
    @if($selectedLog)
    <div class="space-y-4">
        <!-- Basic Info -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu</label>
                <div class="text-sm text-gray-900 dark:text-white">{{ $selectedLog->created_date->format("d/m/Y H:i:s") }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                <div class="text-sm text-gray-900 dark:text-white">{{ $selectedLog->user ? $selectedLog->user->name : "System" }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Event</label>
                <div class="text-sm">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        @if($selectedLog->event === "created") bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($selectedLog->event === "updated") bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($selectedLog->event === "deleted") bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @elseif($selectedLog->event === "login") bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                        {{ ucfirst($selectedLog->event) }}
                    </span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tabel</label>
                <div class="text-sm text-gray-900 dark:text-white">{{ ucfirst(str_replace("_", " ", $selectedLog->table_name)) }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Record ID</label>
                <div class="text-sm text-gray-900 dark:text-white">{{ $selectedLog->record_id ?? "-" }}</div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Address</label>
                <div class="text-sm text-gray-900 dark:text-white">{{ $selectedLog->ip_address }}</div>
            </div>
        </div>
        
        <!-- User Agent -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User Agent</label>
            <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                {{ $selectedLog->user_agent }}
            </div>
        </div>
        
        <!-- Changes -->
        @if($selectedLog->changes && count($selectedLog->changes) > 0)
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detail Perubahan</label>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <pre class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ json_encode($selectedLog->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif
        
        <!-- Created By -->
        @if($selectedLog->creator)
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dicatat Oleh</label>
            <div class="text-sm text-gray-900 dark:text-white">{{ $selectedLog->creator->name }}</div>
        </div>
        @endif
    </div>
    @endif';
@endphp

{{-- Custom Layout untuk Activity Log tanpa tombol create dan action kolom --}}
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pageTitle }}</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $pageDescription }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="space-y-4">
            <!-- Search -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" wire:model.live="search" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" 
                               placeholder="{{ $searchPlaceholder }}">
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <select wire:model.live="perPage" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                </div>
            </div>

            <!-- Additional Filters -->
            {!! $additionalFilters !!}
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        {!! $tableHeaders !!}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($records as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        {!! $tableRow($log) !!}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="viewLog({{ $log->id }})" 
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $tableColspan }}" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ $emptyStateTitle }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $emptyStateDescription }}</p>
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
</div>

<!-- Modal -->
@if($showModal)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Log Aktivitas</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="mt-4">
                {!! $modalContent !!}
            </div>
            
            <div class="mt-6 flex justify-end">
                <button wire:click="closeModal" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif