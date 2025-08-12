{{-- Modal untuk Copy Link --}}
@if($showLinkModal)
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="link-modal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    Public Form Link
                </h3>
                <button wire:click="closeLinkModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Copy link berikut untuk membagikan form:
                </label>
                <div class="flex">
                    <input 
                        type="text" 
                        value="{{ $generatedLink }}" 
                        readonly 
                        id="linkInput"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                    >
                    <button 
                        onclick="copyToClipboard()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Copy
                    </button>
                </div>
                <div id="copyStatus" class="mt-2 text-sm text-green-600 hidden">
                    Link berhasil disalin!
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end space-x-3">
                <button 
                    wire:click="closeLinkModal"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                    Tutup
                </button>
                <a 
                    href="{{ $generatedLink }}" 
                    target="_blank"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                    Lihat Form
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const input = document.getElementById('linkInput');
    input.select();
    input.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        document.getElementById('copyStatus').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('copyStatus').classList.add('hidden');
        }, 3000);
    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Gagal menyalin link. Silakan copy manual.');
    }
}
</script>
@endif