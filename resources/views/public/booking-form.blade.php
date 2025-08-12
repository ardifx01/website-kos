<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Booking Kamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Form Booking Kamar</h1>
                <p class="text-gray-600">Silakan lengkapi form di bawah ini untuk melakukan reservasi kamar</p>
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="alert-container" class="mb-6"></div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            <form id="booking-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label for="nomor_hp" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="nomor_hp" name="nomor_hp" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="pekerjaan" name="pekerjaan" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Alamat KTP -->
                    <div class="md:col-span-2">
                        <label for="alamat_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Sesuai KTP <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat_ktp" name="alamat_ktp" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"></textarea>
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Alamat Domisili -->
                    <div class="md:col-span-2">
                        <label for="alamat_domisili" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Domisili <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="sama_dengan_ktp" class="mr-2">
                            <label for="sama_dengan_ktp" class="text-sm text-gray-600">Sama dengan alamat KTP</label>
                        </div>
                        <textarea id="alamat_domisili" name="alamat_domisili" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"></textarea>
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Tipe Kamar -->
                    <div>
                        <label for="tipe_kamar" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Kamar <span class="text-red-500">*</span>
                        </label>
                        <select id="tipe_kamar" name="tipe_kamar" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih Tipe Kamar</option>
                            <option value="deluxe">Deluxe Room</option>
                            <option value="premium">Premium Room</option>
                        </select>
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Jumlah Orang -->
                    <div>
                        <label for="jumlah_orang" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Orang <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="jumlah_orang" name="jumlah_orang" min="1" max="10" value="1" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div>
                        <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Tambahan
                        </label>
                        <textarea id="catatan" name="catatan" rows="3"
                                  placeholder="Permintaan khusus atau catatan tambahan..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"></textarea>
                        <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" id="submit-btn"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <span id="submit-text">Kirim Booking</span>
                        <svg id="loading-spinner" class="animate-spin -mr-1 ml-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">
                        Dengan mengisi form ini, Anda setuju dengan syarat dan ketentuan yang berlaku.
                    </p>
                </div>
            </form>
        </div>

        <!-- Success Modal -->
        <div id="success-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Booking Berhasil!</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Terima kasih! Booking Anda telah berhasil dikirim. Tim kami akan segera menghubungi Anda untuk konfirmasi.
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button id="close-modal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Handle checkbox for same address
        document.getElementById('sama_dengan_ktp').addEventListener('change', function() {
            const alamatKtp = document.getElementById('alamat_ktp').value;
            const alamatDomisili = document.getElementById('alamat_domisili');
            
            if (this.checked) {
                alamatDomisili.value = alamatKtp;
            } else {
                alamatDomisili.value = '';
            }
        });

        // Update domisili when KTP address changes and checkbox is checked
        document.getElementById('alamat_ktp').addEventListener('input', function() {
            const checkbox = document.getElementById('sama_dengan_ktp');
            if (checkbox.checked) {
                document.getElementById('alamat_domisili').value = this.value;
            }
        });

        // Show alert function
        function showAlert(message, type = 'error') {
            const alertContainer = document.getElementById('alert-container');
            const alertColors = {
                'error': 'bg-red-50 border-red-200 text-red-800',
                'success': 'bg-green-50 border-green-200 text-green-800'
            };
            
            alertContainer.innerHTML = `
                <div class="border-l-4 ${alertColors[type]} p-4 rounded">
                    <p class="text-sm">${message}</p>
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
                const input = document.getElementById(field);
                const errorDiv = input.nextElementSibling;
                
                if (input && errorDiv && errorDiv.classList.contains('error-message')) {
                    input.classList.add('border-red-500');
                    errorDiv.textContent = errors[field][0];
                    errorDiv.classList.remove('hidden');
                }
            });
        }

        // Handle form submission
        document.getElementById('booking-form').addEventListener('submit', async function(e) {
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
                const response = await fetch(`{{ route('public.booking-form.store', $token) }}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success modal
                    document.getElementById('success-modal').classList.remove('hidden');
                    // Reset form
                    this.reset();
                } else {
                    if (data.errors) {
                        showErrors(data.errors);
                    }
                    showAlert(data.message || 'Terjadi kesalahan saat mengirim booking.');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            } finally {
                // Re-enable button and hide loading
                submitBtn.disabled = false;
                submitText.textContent = 'Kirim Booking';
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
    </script>
</body>
</html>