<x-app-layout>
    <div x-data="{
        isModalOpen: false,
        actionUrl: '',
    
        openDeleteModal(url) {
            this.actionUrl = url;
            this.isModalOpen = true;
        }
    }">

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 min-h-[85vh] p-6 lg:p-8">

            <div
                class="flex justify-between items-center mb-6 bg-gradient-to-r from-red-50 to-white p-8 rounded-3xl border border-red-100/50">
                <div class="pr-4">
                    <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">
                        Daftar <span class="text-red-600">Galeri Foto</span>
                    </h1>
                    <p class="text-gray-500 mt-2 text-sm md:text-base">
                        Upload hasil jepretan terbaik untuk ditampilkan di Galeri.
                    </p>
                </div>
                <a href="{{ route('admin.galleries.create') }}"
                    class="px-2.5 py-2.5 bg-gray-700 hover:bg-red-700 text-white rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                <div class="overflow-x-auto hide-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr
                                class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                <th class="p-4">Foto</th>
                                <th class="p-4">Paket Terkait</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($galleries as $gallery)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4">
                                        <img src="{{ asset('storage/' . $gallery->image_path) }}"
                                            class="w-36 h-36 object-cover rounded-xl bg-gray-100 shadow-sm border border-gray-200">
                                    </td>

                                    <td class="p-4 text-gray-600">
                                        @if ($gallery->package)
                                            <span
                                                class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-bold border border-blue-100 shadow-sm">
                                                {{ $gallery->package->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Umum</span>
                                        @endif
                                    </td>

                                    <td class="p-4">
                                        @if ($gallery->is_featured)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                Featured
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                Standar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('admin.galleries.edit', $gallery->id) }}"
                                                class="p-2 bg-white border border-gray-200 rounded-lg text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition shadow-sm"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <button
                                                @click="openDeleteModal('{{ route('admin.galleries.destroy', $gallery->id) }}')"
                                                type="button"
                                                class="p-2 bg-white border border-gray-200 rounded-lg text-red-600 hover:bg-red-50 hover:border-red-200 transition shadow-sm"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-50 rounded-full p-4 mb-3">
                                                <svg class="w-10 h-10 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-500">Belum ada foto galeri.</p>
                                            <p class="text-sm mt-1">Klik tombol tambah di atas untuk mengupload foto
                                                pertama.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
                    @click="isModalOpen = false"></div>

                <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full max-w-sm overflow-hidden transition-all transform bg-white rounded-2xl shadow-xl">

                    <div class="p-6 text-center">
                        <div
                            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 text-red-600 bg-red-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900">Hapus Foto?</h3>
                        <p class="mt-2 text-sm text-gray-500">Foto yang dihapus tidak bisa dikembalikan.</p>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                        <form :action="actionUrl" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">

                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none sm:text-sm transition">
                                Ya, Hapus
                            </button>
                        </form>

                        <button @click="isModalOpen = false" type="button"
                            class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm transition">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
