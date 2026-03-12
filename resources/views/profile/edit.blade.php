<x-app-layout>
    <div class="py-8 px-4 lg:px-16 bg-gray-50 min-h-screen" x-data="{
        editProfile: {{ $errors->has('name') || $errors->has('email') || $errors->has('phone') ? 'true' : 'false' }},
        editPassword: {{ $errors->hasBag('updatePassword') ? 'true' : 'false' }},
        showCurr: false,
        showNew: false,
        showConf: false
    }">

        <div class="max-w-7xl bg-white rounded-3xl shadow-sm py-10 border border-gray-200 mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center mb-8 gap-3">
                <a href="{{ route('home') }}" class="group flex items-center">
                    <svg class="w-6 h-6 text-gray-700 group-hover:text-red-700 transition-colors duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-red-700 font-bold text-2xl lg:text-3xl tracking-tighter">Profil Anda</h1>
            </div>


            <div class="space-y-8">

                <div
                    class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300">
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-gray-100 pb-4 gap-4">
                        <div>
                            <h3 class="text-base lg:text-lg font-bold text-gray-900">Informasi Profil</h3>
                            <p class="text-sm text-gray-500"
                                x-text="editProfile ? 'Perbarui nama dan nomor WhatsApp/HP Anda.' : 'Detail informasi akun Anda saat ini.'">
                            </p>
                        </div>

                        <button x-show="!editProfile" @click="editProfile = true" type="button"
                            class="text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 px-5 py-2.5 rounded-full transition shadow-sm w-full sm:w-auto">
                            Edit Profil
                        </button>
                    </div>

                    <div x-show="!editProfile" x-transition.opacity.duration.300ms class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 border-b border-gray-50 pb-4">
                            <div class="text-sm font-semibold text-gray-400">Nama Lengkap</div>
                            <div class="md:col-span-2 text-sm lg:text-base font-bold text-gray-900">{{ $user->name }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 border-b border-gray-50 pb-4">
                            <div class="text-sm font-semibold text-gray-400">Email</div>
                            <div class="md:col-span-2 text-sm lg:text-base font-bold text-gray-900">{{ $user->email }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 pb-2">
                            <div class="text-sm font-semibold text-gray-400">Nomor WhatsApp / HP</div>
                            <div class="md:col-span-2 text-sm lg:text-base font-bold text-gray-900">
                                {{ $user->phone ?? 'Belum diatur' }}</div>
                        </div>

                        @if (session('status') === 'profile-updated')
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                                class="mt-4 p-3 bg-green-50 border border-green-200 rounded-xl flex items-center gap-2 text-green-700 text-sm font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Informasi profil berhasil diperbarui!
                            </div>
                        @endif
                    </div>

                    <form x-show="editProfile" style="display: none;" x-transition.opacity.duration.300ms method="post"
                        action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- PERBAIKAN: Ubah col-span-2 menjadi md:col-span-2 agar tidak merusak grid di mobile --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama
                                    Lengkap</label>
                                <input id="name" name="name" type="text"
                                    value="{{ old('name', $user->name) }}" required
                                    class="block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm sm:text-sm">
                                @error('name')
                                    <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                <input id="email" name="email" type="email"
                                    value="{{ old('email', $user->email) }}" required
                                    class="block w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed shadow-sm sm:text-sm"
                                    readonly title="Email tidak bisa diubah">
                                <p class="text-[11px] text-gray-400 mt-1.5">*Email bersifat permanen dan tidak dapat
                                    diubah.</p>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp
                                    / HP</label>
                                <input id="phone" name="phone" type="tel"
                                    value="{{ old('phone', $user->phone) }}" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="11"
                                    maxlength="15"
                                    class="block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm sm:text-sm">
                                @error('phone')
                                    <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center gap-3 pt-4 border-t border-gray-50">
                            <button type="button" @click="editProfile = false"
                                class="w-full sm:w-auto px-6 py-2.5 rounded-full text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="w-full sm:w-auto bg-gray-900 hover:bg-black text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-sm transition">
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>

                <div
                    class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300">
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-gray-100 pb-4 gap-4">
                        <div>
                            <h3 class="text-base lg:text-lg font-bold text-gray-900">Keamanan</h3>
                            <p class="text-sm text-gray-500"
                                x-text="editPassword ? 'Buat password baru yang kuat.' : 'Kelola kata sandi akun Anda.'">
                            </p>
                        </div>

                        <button x-show="!editPassword" @click="editPassword = true" type="button"
                            class="text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 px-5 py-2.5 rounded-full transition shadow-sm w-full sm:w-auto">
                            Ubah Password
                        </button>
                    </div>

                    <div x-show="!editPassword" x-transition.opacity.duration.300ms class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 pb-2">
                            <div class="text-sm font-semibold text-gray-400">Password</div>
                            <div
                                class="md:col-span-2 text-sm lg:text-base font-bold text-gray-900 tracking-widest leading-none">
                                ••••••••</div>
                        </div>

                        @if (session('status') === 'password-updated')
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                                class="mt-4 p-3 bg-green-50 border border-green-200 rounded-xl flex items-center gap-2 text-green-700 text-sm font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Password berhasil diubah!
                            </div>
                        @endif
                    </div>

                    <form x-show="editPassword" style="display: none;" x-transition.opacity.duration.300ms
                        method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <label for="update_password_current_password"
                                class="block text-sm font-bold text-gray-700 mb-2">Password Saat Ini</label>
                            <div class="relative w-full md:w-2/3">
                                <input id="update_password_current_password" name="current_password"
                                    :type="showCurr ? 'text' : 'password'" required
                                    class="block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm sm:text-sm">
                                <button type="button" @click="showCurr = !showCurr"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <span x-show="!showCurr"
                                        class="text-[10px] font-bold bg-gray-100 px-2 py-1 rounded">LIHAT</span>
                                    <span x-show="showCurr"
                                        class="text-[10px] font-bold bg-red-100 text-red-600 px-2 py-1 rounded"
                                        style="display: none;">TUTUP</span>
                                </button>
                            </div>
                            @error('current_password', 'updatePassword')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="update_password_password"
                                class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                            <div class="relative w-full md:w-2/3">
                                <input id="update_password_password" name="password"
                                    :type="showNew ? 'text' : 'password'" required
                                    class="block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm sm:text-sm">
                                <button type="button" @click="showNew = !showNew"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <span x-show="!showNew"
                                        class="text-[10px] font-bold bg-gray-100 px-2 py-1 rounded">LIHAT</span>
                                    <span x-show="showNew"
                                        class="text-[10px] font-bold bg-red-100 text-red-600 px-2 py-1 rounded"
                                        style="display: none;">TUTUP</span>
                                </button>
                            </div>
                            @error('password', 'updatePassword')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="update_password_password_confirmation"
                                class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <div class="relative w-full md:w-2/3">
                                <input id="update_password_password_confirmation" name="password_confirmation"
                                    :type="showConf ? 'text' : 'password'" required
                                    class="block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm sm:text-sm">
                                <button type="button" @click="showConf = !showConf"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <span x-show="!showConf"
                                        class="text-[10px] font-bold bg-gray-100 px-2 py-1 rounded">LIHAT</span>
                                    <span x-show="showConf"
                                        class="text-[10px] font-bold bg-red-100 text-red-600 px-2 py-1 rounded"
                                        style="display: none;">TUTUP</span>
                                </button>
                            </div>
                            @error('password_confirmation', 'updatePassword')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center gap-3 pt-4 border-t border-gray-50">
                            <button type="button" @click="editPassword = false"
                                class="w-full sm:w-auto px-6 py-2.5 rounded-full text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-sm transition">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
