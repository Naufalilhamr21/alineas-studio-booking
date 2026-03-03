<x-app-layout>
    <div x-data="{
        // --- State Image Upload ---
        photoName: null,
        photoPreview: null,
    
        // --- State Modal Dinamis ---
        isModalOpen: false,
        modalType: 'confirm', // 'error', 'confirm', 'loading'
        modalTitle: '',
        modalMessage: '',
    
        // 1. Fungsi Handle Upload Gambar
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
    
            // Validasi Ukuran (> 2MB)
            if (file.size > 2 * 1024 * 1024) {
                this.showError('Maksimal ukuran file adalah 2MB!');
                this.$refs.photo.value = '';
                this.photoName = null;
                this.photoPreview = null;
                return;
            }
    
            // Validasi Tipe File
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                this.showError('Harap upload file gambar (JPG/PNG)!');
                this.$refs.photo.value = '';
                this.photoName = null;
                this.photoPreview = null;
                return;
            }
    
            // Preview
            this.photoName = file.name;
            const reader = new FileReader();
            reader.onload = (e) => { this.photoPreview = e.target.result; };
            reader.readAsDataURL(file);
        },
    
        // 2. Fungsi Validasi Manual Form & Buka Konfirmasi
        validateAndConfirm() {
            // Validasi: Untuk Galeri, HANYA Foto yang Wajib
            if (this.$refs.photo.files.length === 0 && !this.photoPreview) {
                return this.showError('Silakan pilih file foto terlebih dahulu.');
            }
    
            // Jika Lolos, Tampilkan Konfirmasi
            this.modalType = 'confirm';
            this.modalTitle = 'Upload Foto?';
            this.modalMessage = 'Pastikan foto yang dipilih sudah benar.';
            this.isModalOpen = true;
        },
    
        // 3. Fungsi Menampilkan Error
        showError(message) {
            this.modalType = 'error';
            this.modalTitle = 'Data Kurang';
            this.modalMessage = message;
            this.isModalOpen = true;
        },
    
        // 4. Fungsi Submit Akhir (Tampilkan Loading Spinner)
        submitForm() {
            this.modalType = 'loading';
            this.modalTitle = 'Mengupload...';
            this.modalMessage = 'Mohon tunggu sebentar.';
            this.$refs.galleryForm.submit();
        },
    
        // 5. Cek Error dari Server (Laravel Validation) saat Load
        init() {
            let hasServerErrors = {{ $errors->any() ? 'true' : 'false' }};
            if (hasServerErrors) {
                this.showError('Periksa kembali inputan Anda, ada yang tidak sesuai.');
            }
        }
    }" x-init="init()">

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 min-h-[85vh] p-6 lg:p-8">

            <div
                class="flex justify-start gap-5 items-center mb-6 bg-gradient-to-r from-red-50 to-white p-8 rounded-3xl border border-red-100/50">
                <a href="{{ route('admin.galleries.index') }}"
                    class="bg-gray-700 text-white w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-700 transition shadow-sm">
                    <svg class="w-6 h-6 text-white-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-lg md:text-2xl font-extrabold text-gray-900 tracking-tight">
                    Upload <span class="text-red-600">Foto Baru</span>
                </h1>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl text-sm">
                    <p class="font-bold mb-1">Gagal Menyimpan!</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                <form x-ref="galleryForm" action="{{ route('admin.galleries.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="pb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">File Foto <span
                                class="text-red-600">*</span></label>

                        <input type="file" name="image" id="image" class="hidden" x-ref="photo"
                            accept="image/png, image/jpeg, image/jpg" x-on:change="handleFileUpload($event)">

                        <div class="mt-2" x-show="!photoPreview">
                            <div x-on:click.prevent="$refs.photo.click()"
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-red-50 hover:border-red-400 transition"
                                id="dropzone-container">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 font-semibold">Klik untuk upload foto</p>
                                    <p class="text-xs text-gray-500">Format: JPG, PNG (Max 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 relative" x-show="photoPreview" style="display: none;">
                            <span
                                class="block w-full h-80 rounded-xl bg-contain bg-center bg-no-repeat shadow-sm border border-gray-200 bg-gray-50"
                                x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                            <button type="button" x-on:click.prevent="$refs.photo.click()"
                                class="absolute top-4 right-4 bg-white/90 text-gray-700 hover:text-red-600 px-4 py-2 rounded-full shadow-md text-sm font-bold transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Ganti Foto
                            </button>
                        </div>

                        @error('image')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Paket (Opsional)</label>
                        <select name="package_id" id="package_id"
                            class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm cursor-pointer">
                            <option value="">Umum (Tanpa Kategori Paket)</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}"
                                    {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1.5">*Pilih paket jika Anda ingin foto ini muncul sebagai
                            referensi di halaman paket tersebut.</p>
                    </div>

                    <div class="flex items-center p-4 border border-gray-200 rounded-xl bg-gray-50 hover:bg-red-50 hover:border-red-200 transition cursor-pointer"
                        onclick="document.getElementById('feat').click()">
                        <input type="checkbox" name="is_featured" id="feat" value="1"
                            {{ old('is_featured') ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer pointer-events-none">
                        <div class="ml-3">
                            <label class="text-sm font-bold text-gray-800 cursor-pointer pointer-events-none">
                                Jadikan Featured?
                            </label>
                            <p class="text-xs text-gray-500 mt-0.5">Foto ini akan diprioritaskan tampil di halaman utama
                                / beranda.</p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" @click="validateAndConfirm()"
                            class="px-8 py-3 text-sm lg:text-md bg-red-600 hover:bg-red-700 text-white font-bold rounded-full shadow-md transition transform hover:-translate-y-0.5">
                            Upload Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="isModalOpen"
                class="fixed inset-0 z-[99] flex items-center justify-center min-h-screen px-4 py-6 sm:px-0"
                style="display: none;">

                <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-70 backdrop-blur-sm"
                    @click="if(modalType !== 'loading') isModalOpen = false"></div>

                <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full max-w-sm overflow-hidden transition-all transform bg-white rounded-2xl shadow-xl">

                    <div class="p-6 text-center">

                        <template x-if="modalType === 'error'">
                            <div
                                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 text-red-600 bg-red-100">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </template>

                        <template x-if="modalType === 'confirm'">
                            <div
                                class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 text-blue-600 bg-blue-100">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                            </div>
                        </template>

                        <template x-if="modalType === 'loading'">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4">
                                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </template>

                        <h3 class="text-lg font-bold text-gray-900" x-text="modalTitle"></h3>
                        <p class="mt-2 text-sm text-gray-500" x-text="modalMessage"></p>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">

                        <template x-if="modalType === 'error'">
                            <button @click="isModalOpen = false" type="button"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none sm:text-sm transition">
                                OK
                            </button>
                        </template>

                        <template x-if="modalType === 'confirm'">
                            <div class="w-full flex flex-col-reverse sm:flex-row gap-3">
                                <button @click="submitForm()" type="button"
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none sm:text-sm transition">
                                    Ya, Upload
                                </button>
                                <button @click="isModalOpen = false" type="button"
                                    class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm transition">
                                    Batal
                                </button>
                            </div>
                        </template>

                    </div>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>
