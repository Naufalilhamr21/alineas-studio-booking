{{-- <x-app-layout>
    <div x-data="{
        // --- State Image Upload ---
        photoName: null,
        photoPreview: null,
    
        // --- State Modal Dinamis ---
        isModalOpen: false,
        modalType: 'confirm', // 'error', 'confirm', 'loading'
        modalTitle: '',
        modalMessage: '',
    
        // 1. Fungsi Handle Upload Gambar (Termasuk Validasi Ukuran & Tipe)
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
            const name = document.getElementById('name').value.trim();
            const tagline = document.getElementById('tagline').value.trim();
            const price = document.getElementById('price').value;
            const duration = document.getElementById('duration_minutes').value;
            const benefit = document.getElementById('benefit').value.trim();
            const thumbnail = this.$refs.photo.files.length;
    
            if (!name) return this.showError('Nama Paket wajib diisi.');
            if (!tagline) return this.showError('Tagline wajib diisi.');
            if (!price) return this.showError('Harga Paket wajib diisi.');
            if (!duration) return this.showError('Durasi Paket wajib diisi.');
            if (!benefit) return this.showError('List Benefit wajib diisi.');
            if (!thumbnail && !this.photoPreview) return this.showError('Gambar Thumbnail wajib diupload.');
    
            // Jika Lolos, Tampilkan Konfirmasi
            this.modalType = 'confirm';
            this.modalTitle = 'Simpan Paket?';
            this.modalMessage = 'Pastikan data yang Anda masukkan sudah benar.';
            this.isModalOpen = true;
        },
    
        // 3. Fungsi Menampilkan Error
        showError(message) {
            this.modalType = 'error';
            this.modalTitle = 'Data Kurang atau Salah';
            this.modalMessage = message;
            this.isModalOpen = true;
        },
    
        // 4. Fungsi Submit Akhir (Tampilkan Loading Spinner)
        submitForm() {
            this.modalType = 'loading';
            this.modalTitle = 'Menyimpan...';
            this.modalMessage = 'Mohon tunggu sebentar.';
            this.$refs.createForm.submit();
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
                <a href="{{ route('admin.packages.index') }}"
                    class="bg-gray-700 text-white w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-700 transition">
                    <svg class="w-6 h-6 text-white-700"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-lg md:text-2xl font-extrabold text-gray-900 tracking-tight">
                    Tambah <span class="text-red-600">Paket Baru</span>
                </h1>
            </div>

            <div class="p-8 bg-white rounded-2xl border border-gray-200 shadow-sm">

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

                <form x-ref="createForm" action="{{ route('admin.packages.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Package Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: The Family">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tagline (Deskripsi Singkat) <span
                                    class="text-red-500">*</span></label></label>
                            <input type="text" name="tagline" id="tagline"
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: Abadikan kehangatan cinta keluarga." value="{{ old('tagline') }}">
                            @error('tagline')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Price (IDR) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: 150000">
                            @error('price')
                                <p class="text-red-500 text-sm font-semibold mt-1 flex items-center gap-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Duration (Minutes) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="duration_minutes" id="duration_minutes"
                                value="{{ old('duration_minutes') }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: 60">
                            @error('duration_minutes')
                                <p class="text-red-500 text-sm font-semibold mt-1 flex items-center gap-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">List Benefit <span
                                    class="text-red-500">*</span></label></label>
                            <textarea name="benefit" id="benefit" rows="4"
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth:&#10;• 30 Menit sesi foto&#10;• 10 Soft file edit">{{ old('benefit') }}</textarea>
                            @error('benefit')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Thumbnail Image <span
                                    class="text-red-500">*</span></label>

                            <input type="file" name="thumbnail" id="thumbnail" class="hidden" x-ref="photo"
                                accept="image/png, image/jpeg, image/jpg" x-on:change="handleFileUpload($event)">

                            <div class="mt-2" x-show="!photoPreview">
                                <div x-on:click.prevent="$refs.photo.click()"
                                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-red-50 hover:border-red-400 transition">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-500"><span class="font-semibold">Click to upload
                                                image</span></p>
                                        <p class="text-xs text-gray-500">JPG, PNG (Max 2MB)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 relative" x-show="photoPreview" style="display: none;">
                                <span class="block w-full h-56 rounded-xl bg-cover bg-center bg-no-repeat shadow-sm"
                                    x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                </span>
                                <button type="button" x-on:click.prevent="$refs.photo.click()"
                                    class="absolute top-2 right-2 bg-white/90 text-gray-700 hover:text-red-600 p-2 rounded-full shadow-sm text-xs font-bold transition">
                                    Change Image
                                </button>
                            </div>
                            @error('thumbnail')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2 flex items-center gap-3">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">Set this package as
                                Active
                                immediately</label>
                        </div>

                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button" @click="validateAndConfirm()"
                            class="px-6 py-3 text-sm lg:text-md bg-red-600 hover:bg-red-700 text-white font-bold rounded-full shadow-sm transition">
                            Save Package
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
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                            </div>
                        </template>

                        <template x-if="modalType === 'loading'">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4">
                                <svg class="animate-spin h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg"
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
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none sm:text-sm transition">
                                    Ya, Simpan
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
</x-app-layout> --}}


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
    
        // 1. Fungsi Handle Upload Gambar (Termasuk Validasi Ukuran & Tipe)
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
            const name = document.getElementById('name').value.trim();
            const tagline = document.getElementById('tagline').value.trim();
            const price = document.getElementById('price').value;
            const duration = document.getElementById('duration_minutes').value;
            const maxPax = document.getElementById('max_pax').value;
            const extraPrice = document.getElementById('extra_price_per_pax').value;
            const benefit = document.getElementById('benefit').value.trim();
            const thumbnail = this.$refs.photo.files.length;
    
            if (!name) return this.showError('Nama Paket wajib diisi.');
            if (!tagline) return this.showError('Tagline wajib diisi.');
            if (!price) return this.showError('Harga Paket wajib diisi.');
            if (!duration) return this.showError('Durasi Paket wajib diisi.');
            if (!maxPax) return this.showError('Maksimal orang (Max Pax) wajib diisi.');
            if (!extraPrice) return this.showError('Biaya tambahan per orang wajib diisi (isi 0 jika tidak ada).');
            if (!benefit) return this.showError('List Benefit wajib diisi.');
            if (!thumbnail && !this.photoPreview) return this.showError('Gambar Thumbnail wajib diupload.');
    
            // Jika Lolos, Tampilkan Konfirmasi
            this.modalType = 'confirm';
            this.modalTitle = 'Simpan Paket?';
            this.modalMessage = 'Pastikan data yang Anda masukkan sudah benar.';
            this.isModalOpen = true;
        },
    
        // 3. Fungsi Menampilkan Error
        showError(message) {
            this.modalType = 'error';
            this.modalTitle = 'Data Kurang atau Salah';
            this.modalMessage = message;
            this.isModalOpen = true;
        },
    
        // 4. Fungsi Submit Akhir (Tampilkan Loading Spinner)
        submitForm() {
            this.modalType = 'loading';
            this.modalTitle = 'Menyimpan...';
            this.modalMessage = 'Mohon tunggu sebentar.';
            this.$refs.createForm.submit();
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
                <a href="{{ route('admin.packages.index') }}"
                    class="bg-gray-700 text-white w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-700 transition">
                    <svg class="w-6 h-6 text-white-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-lg md:text-2xl font-extrabold text-gray-900 tracking-tight">
                    Tambah <span class="text-red-600">Paket Baru</span>
                </h1>
            </div>

            <div class="p-8 bg-white rounded-2xl border border-gray-200 shadow-sm">

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

                <form x-ref="createForm" action="{{ route('admin.packages.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Package Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: The Family">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tagline (Deskripsi Singkat) <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="tagline" id="tagline"
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: Abadikan kehangatan cinta keluarga." value="{{ old('tagline') }}">
                            @error('tagline')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Price (IDR) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: 150000">
                            @error('price')
                                <p class="text-red-500 text-sm font-semibold mt-1 flex items-center gap-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Duration (Minutes) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="duration_minutes" id="duration_minutes"
                                value="{{ old('duration_minutes') }}" required
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: 60">
                            @error('duration_minutes')
                                <p class="text-red-500 text-sm font-semibold mt-1 flex items-center gap-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- INPUT BARU: MAX PAX --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Maksimal Orang (Max Pax) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="max_pax" id="max_pax" value="{{ old('max_pax', 1) }}"
                                required min="1"
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: 4">
                            @error('max_pax')
                                <p class="text-red-500 text-sm font-semibold mt-1 flex items-center gap-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- INPUT BARU: EXTRA PRICE PER PAX --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Biaya Tambahan / Orang (IDR) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="extra_price_per_pax" id="extra_price_per_pax"
                                value="{{ old('extra_price_per_pax', 0) }}" required min="0"
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth: 25000 (Isi 0 jika tidak ada)">
                            @error('extra_price_per_pax')
                                <p class="text-red-500 text-sm font-semibold mt-1 flex items-center gap-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">List Benefit <span
                                    class="text-red-500">*</span></label>
                            <textarea name="benefit" id="benefit" rows="4"
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm transition"
                                placeholder="Cth:&#10;• 30 Menit sesi foto&#10;• 10 Soft file edit">{{ old('benefit') }}</textarea>
                            @error('benefit')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Thumbnail Image <span
                                    class="text-red-500">*</span></label>

                            <input type="file" name="thumbnail" id="thumbnail" class="hidden" x-ref="photo"
                                accept="image/png, image/jpeg, image/jpg" x-on:change="handleFileUpload($event)">

                            <div class="mt-2" x-show="!photoPreview">
                                <div x-on:click.prevent="$refs.photo.click()"
                                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-red-50 hover:border-red-400 transition">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-500"><span class="font-semibold">Click to upload
                                                image</span></p>
                                        <p class="text-xs text-gray-500">JPG, PNG (Max 2MB)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 relative" x-show="photoPreview" style="display: none;">
                                <span class="block w-full h-56 rounded-xl bg-cover bg-center bg-no-repeat shadow-sm"
                                    x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                </span>
                                <button type="button" x-on:click.prevent="$refs.photo.click()"
                                    class="absolute top-2 right-2 bg-white/90 text-gray-700 hover:text-red-600 p-2 rounded-full shadow-sm text-xs font-bold transition">
                                    Change Image
                                </button>
                            </div>
                            @error('thumbnail')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2 flex items-center gap-3">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                                class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">Set this package as
                                Active
                                immediately</label>
                        </div>

                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button" @click="validateAndConfirm()"
                            class="px-6 py-3 text-sm lg:text-md bg-red-600 hover:bg-red-700 text-white font-bold rounded-full shadow-sm transition">
                            Save Package
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
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                            </div>
                        </template>

                        <template x-if="modalType === 'loading'">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4">
                                <svg class="animate-spin h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg"
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
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none sm:text-sm transition">
                                    Ya, Simpan
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
