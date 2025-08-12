<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Complaint & Keluhan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Form Complaint & Keluhan</h1>
                <p class="text-gray-600">Sampaikan keluhan atau masalah Anda kepada kami. Tim customer service akan segera menangani keluhan Anda.</p>
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="alert-container" class="mb-6"></div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form id="complaint-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="nomor_hp" name="nomor_hp" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Tipe Kamar -->
                    <div>
                        <label for="tipe_kamar" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Kamar <span class="text-red-500">*</span>
                        </label>
                        <select id="tipe_kamar" name="tipe_kamar" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih Tipe Kamar</option>
                            @foreach(\App\Models\ComplaintForm::getTipeKamarOptions() as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Subjek -->
                    <div class="md:col-span-2">
                        <label for="subjek" class="block text-sm font-medium text-gray-700 mb-2">
                            Subjek Complaint <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="subjek" name="subjek" required
                               placeholder="Contoh: AC tidak dingin, WiFi lemot, pelayanan kurang baik, dll"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Kategori -->
                    <div class="md:col-span-2">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori Keluhan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach(\App\Models\ComplaintForm::getKategoriOptions() as $key => $value)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="kategori" value="{{ $key }}" class="mr-3 text-orange-500 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">{{ $value }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Detail Keluhan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" required
                                  placeholder="Jelaskan secara detail keluhan atau masalah yang Anda alami. Semakin detail informasi yang Anda berikan, semakin cepat kami dapat menangani keluhan Anda..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200"></textarea>
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 10 karakter</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" id="submit-btn"
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <span id="submit-text">Kirim Complaint</span>
                        <svg id="loading-spinner" class="animate-spin -mr-1 ml-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Data Anda akan dijaga kerahasiaan dan hanya digunakan untuk menangani keluhan ini.
                        </span>
                    </p>
                </div>
            </form>
        </div>

        <!-- Success Modal -->
        <div id="success-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Complaint Terkirim!</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Terima kasih atas laporan Anda. Tim customer service kami akan segera menangani keluhan Anda dan menghubungi Anda melalui email atau telepon.
                        </p>
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-700">
                                <strong>ID Complaint:</strong> <span id="complaint-id"></span>
                            </p>
                            <p class="text-xs text-blue-600 mt-1">
                                Simpan ID ini untuk referensi follow-up
                            </p>
                        </div>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button id="close-modal" class="px-4 py-2 bg-orange-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi Response Time</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Urgent:</strong> Response dalam 1-2 jam</li>
                            <li><strong>Normal:</strong> Response dalam 24 jam</li>
                            <li><strong>Follow-up:</strong> Tim akan menghubungi Anda via email/telepon</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show alert function
        function showAlert(message, type = 'error') {
            const alertContainer = document.getElementById('alert-container');
            const alertColors = {
                'error': 'bg-red-50 border-red-200 text-red-800',
                'success': 'bg-green-50 border-green-200 text-green-800'
            };
            
            alertContainer.innerHTML = `
                <div class="border-l-4 ${alertColors[type]} p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${type === 'error' ? 
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>' :
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                                }
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">${message}</p>
                        </div>
                    </div>
                </div>
            `;
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Clear error messages
        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('border-red-500');
            });
            document.getElementById('alert-container').innerHTML = '';
        }

        // Show field errors
        function showErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.getElementById(field) || document.querySelector(`input[name="${field}"]:checked`)?.parentElement.parentElement;
                
                if (input) {
                    if (field === 'kategori') {
                        // Handle radio button errors
                        const kategoriFielset = input;
                        const errorDiv = kategoriFielset.querySelector('.error-message');
                        if (errorDiv) {
                            errorDiv.textContent = errors[field][0];
                            errorDiv.classList.remove('hidden');
                        }
                    } else {
                        // Handle regular input errors
                        const errorDiv = input.nextElementSibling;
                        if (errorDiv && errorDiv.classList.contains('error-message')) {
                            input.classList.add('border-red-500');
                            errorDiv.textContent = errors[field][0];
                            errorDiv.classList.remove('hidden');
                        }
                    }
                }
            });
        }

        // Handle form submission
        document.getElementById('complaint-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const loadingSpinner = document.getElementById('loading-spinner');
            
            // Clear previous errors
            clearErrors();
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitText.textContent = 'Mengirim...';
            loadingSpinner.classList.remove('hidden');
            
            try {
                const formData = new FormData(this);
                const response = await fetch(`{{ route('public.complaint-form.store', $token) }}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success modal
                    document.getElementById('complaint-id').textContent = '#' + data.complaint_id;
                    document.getElementById('success-modal').classList.remove('hidden');
                    // Reset form
                    this.reset();
                } else {
                    if (data.errors) {
                        showErrors(data.errors);
                    }
                    showAlert(data.message || 'Terjadi kesalahan saat mengirim complaint.');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            } finally {
                // Re-enable button and hide loading
                submitBtn.disabled = false;
                submitText.textContent = 'Kirim Complaint';
                loadingSpinner.classList.add('hidden');
            }
        });

        // Close modal
        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('success-modal').classList.add('hidden');
        });

        // Close modal when clicking outside
        document.getElementById('success-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Character counter for description
        const deskripsiTextarea = document.getElementById('deskripsi');
        deskripsiTextarea.addEventListener('input', function() {
            const charCount = this.value.length;
            const minChars = 10;
            
            // Find or create character counter
            let counter = this.parentElement.querySelector('.char-counter');
            if (!counter) {
                counter = document.createElement('p');
                counter.className = 'char-counter text-xs mt-1';
                this.parentElement.appendChild(counter);
            }
            
            if (charCount < minChars) {
                counter.textContent = `${charCount}/${minChars} karakter (minimal ${minChars - charCount} lagi)`;
                counter.className = 'char-counter text-xs text-red-500 mt-1';
            } else {
                counter.textContent = `${charCount} karakter`;
                counter.className = 'char-counter text-xs text-green-600 mt-1';
            }
        });
    </script>
</body>
</html>