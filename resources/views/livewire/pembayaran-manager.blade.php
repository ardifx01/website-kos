<div>
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Manajemen Pembayaran</h1>
        <p class="text-gray-600">Kelola data pembayaran booking kamar</p>
    </div>

    {{-- Action Bar --}}
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            {{-- Search and Filters --}}
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                {{-- Search Input --}}
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Cari pembayaran..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Status Filter --}}
                <select wire:model.live="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>

                {{-- Payment Method Filter --}}
                <select wire:model.live="metodeFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Metode</option>
                    @foreach($metodePembayaranOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button wire:click="openCreateModal" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Pembayaran
                </button>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('idPembayaran')">
                            ID Pembayaran
                            @if($sortField === 'idPembayaran')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Booking
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Penyewa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('jumlah')">
                            Jumlah
                            @if($sortField === 'jumlah')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('tanggalBayar')">
                            Tanggal Bayar
                            @if($sortField === 'tanggalBayar')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('metodePembayaran')">
                            Metode
                            @if($sortField === 'metodePembayaran')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                            wire:click="sortBy('status')">
                            Status
                            @if($sortField === 'status')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $pembayaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $pembayaran->idPembayaran }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                #{{ $pembayaran->booking->idBooking ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pembayaran->booking->penyewa->nama_lengkap ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pembayaran->tanggalBayar ? $pembayaran->tanggalBayar->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $metodePembayaranOptions[$pembayaran->metodePembayaran] ?? $pembayaran->metodePembayaran }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'berhasil' => 'bg-green-100 text-green-800',
                                        'gagal' => 'bg-red-100 text-red-800',
                                        'dibatalkan' => 'bg-gray-100 text-gray-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$pembayaran->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusOptions[$pembayaran->status] ?? $pembayaran->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    {{-- Quick Actions for Pending Payments --}}
                                    @if($pembayaran->status === 'pending')
                                        <button wire:click="confirmPayment({{ $pembayaran->idPembayaran }})"
                                                onclick="return confirm('Konfirmasi pembayaran ini?')"
                                                class="text-green-600 hover:text-green-900 font-medium">
                                            Konfirmasi
                                        </button>
                                        <button wire:click="rejectPayment({{ $pembayaran->idPembayaran }})"
                                                onclick="return confirm('Tolak pembayaran ini?')"
                                                class="text-red-600 hover:text-red-900 font-medium">
                                            Tolak
                                        </button>
                                    @endif
                                    
                                    {{-- Standard Actions --}}
                                    <button wire:click="openViewModal({{ $pembayaran->idPembayaran }})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        Lihat
                                    </button>
                                    <button wire:click="openEditModal({{ $pembayaran->idPembayaran }})" 
                                            class="text-indigo-600 hover:text-indigo-900">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $pembayaran->idPembayaran }})" 
                                            class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m-4 0h4m12 0h4"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data pembayaran</h3>
                                    <p class="text-gray-500">Data pembayaran yang Anda cari tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($records->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $records->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
         wire:click="closeModal">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl bg-white rounded-lg shadow-lg"
             wire:click.stop>
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    @if($recordId)
                        {{ $editTitle }}
                    @else
                        {{ $createTitle }}
                    @endif
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Booking Selection --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Booking</label>
                        <select wire:model="idBooking" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Booking</option>
                            @foreach($bookingOptions as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('idBooking') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Penyewa Selection --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penyewa</label>
                        <select wire:model="idPenyewa" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Penyewa</option>
                            @foreach($penyewaOptions as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('idPenyewa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" 
                               wire:model="jumlah"
                               step="0.01"
                               placeholder="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                        <input type="date" 
                               wire:model="tanggalBayar"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('tanggalBayar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select wire:model="metodePembayaran" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Metode</option>
                            @foreach($metodePembayaranOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('metodePembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @foreach($statusOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Transfer Proof --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer</label>
                        <textarea wire:model="buktiTransfer"
                                  rows="3"
                                  placeholder="Link atau catatan bukti transfer..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                        @error('buktiTransfer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Modal Actions --}}
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" 
                            wire:click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        @if($recordId)
                            Update Pembayaran
                        @else
                            Simpan Pembayaran
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

    {{-- View Modal --}}
    @if($showViewModal && $selectedRecord)
        <div class="fixed inset-0 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             wire:click="closeViewModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl bg-white rounded-lg shadow-lg"
                 wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $viewTitle }}</h3>
                    <button wire:click="closeViewModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID Pembayaran</label>
                            <p class="text-gray-900 font-medium">#{{ $selectedRecord->idPembayaran }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Booking</label>
                            <p class="text-gray-900">#{{ $selectedRecord->booking->idBooking ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Penyewa</label>
                            <p class="text-gray-900">{{ $selectedRecord->booking->penyewa->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jumlah</label>
                            <p class="text-gray-900 font-bold text-lg">Rp {{ number_format($selectedRecord->jumlah, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Bayar</label>
                            <p class="text-gray-900">{{ $selectedRecord->tanggalBayar ? $selectedRecord->tanggalBayar->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Metode Pembayaran</label>
                            <p class="text-gray-900">{{ $metodePembayaranOptions[$selectedRecord->metodePembayaran] ?? $selectedRecord->metodePembayaran }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'berhasil' => 'bg-green-100 text-green-800',
                                    'gagal' => 'bg-red-100 text-red-800',
                                    'dibatalkan' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$selectedRecord->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusOptions[$selectedRecord->status] ?? $selectedRecord->status }}
                            </span>
                        </div>
                    </div>
                    
                    @if($selectedRecord->buktiTransfer)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Bukti Transfer</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $selectedRecord->buktiTransfer }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat</label>
                            <p class="text-gray-700">
                                {{ $selectedRecord->created_at->format('d/m/Y H:i') }}
                                @if($selectedRecord->creator)
                                    <br>oleh {{ $selectedRecord->creator->name }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Diupdate</label>
                            <p class="text-gray-700">
                                {{ $selectedRecord->updated_at->format('d/m/Y H:i') }}
                                @if($selectedRecord->updater)
                                    <br>oleh {{ $selectedRecord->updater->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    @if($selectedRecord->status === 'pending')
                        <button wire:click="confirmPayment({{ $selectedRecord->idPembayaran }})"
                                onclick="return confirm('Konfirmasi pembayaran ini?')"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                            Konfirmasi Pembayaran
                        </button>
                        <button wire:click="rejectPayment({{ $selectedRecord->idPembayaran }})"
                                onclick="return confirm('Tolak pembayaran ini?')"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                            Tolak Pembayaran
                        </button>
                    @endif
                    <button wire:click="closeViewModal"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Loading Indicator --}}
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