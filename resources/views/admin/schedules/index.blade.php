<x-app-layout>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 min-h-[85vh] p-6 lg:p-8" x-data="{
        openDeleteModal: false,
        deleteUrl: '',
        deleteDate: ''
    }">

        {{-- Header Banner --}}
        <div
            class="mb-8 bg-gradient-to-r from-red-50 to-white p-8 rounded-3xl border border-red-100/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    Jadwal <span class="text-red-600">Operasional Studio</span>
                </h1>
                <p class="text-gray-500 mt-2 text-base">Atur tanggal tutup atau ubah jam operasional khusus untuk
                    hari-hari tertentu.</p>
            </div>
            <div class="hidden md:block bg-red-100 p-3 rounded-2xl text-red-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div
                class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <ul class="list-disc pl-5 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Form Tambah Jadwal Khusus (Kolom Kiri) --}}
            <div class="lg:col-span-1">
                {{-- State Form Alpine.js --}}
                <div x-data="{
                    statusTipe: 'tutup',
                    date: '',
                    openTime: '11:00',
                    closeTime: '18:00',
                    showErrors: false
                }"
                    class="bg-white border border-gray-100 rounded-3xl shadow-sm p-6 sticky top-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
                        Atur Jadwal Baru
                    </h2>

                    {{-- Form dengan pencegahan submit jika data tidak valid --}}
                    <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-5"
                        @submit="if (!date || (statusTipe === 'jam_khusus' && (!openTime || !closeTime))) { $event.preventDefault(); showErrors = true; }">
                        @csrf

                        {{-- Input Tanggal --}}
                        <div>
                            <label for="date" class="block text-sm font-bold text-gray-700 mb-2">Pilih Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="date" id="date" x-model="date" min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-100 text-sm transition-all duration-200"
                                :class="showErrors && !date ? 'border-red-400 focus:border-red-400' :
                                    'border-gray-200 focus:border-red-400'">
                            <p x-show="showErrors && !date" class="text-xs text-red-500 mt-1 font-medium">Tanggal wajib
                                dipilih.</p>
                        </div>

                        {{-- Pilihan Status --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Pengaturan <span
                                    class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-all duration-200"
                                    :class="statusTipe === 'tutup' ? 'border-red-500 bg-red-50/50' :
                                        'border-gray-200 bg-gray-50 hover:bg-gray-100'">
                                    <input type="radio" name="status_tipe" value="tutup" x-model="statusTipe"
                                        class="hidden">
                                    <span class="text-sm font-bold"
                                        :class="statusTipe === 'tutup' ? 'text-red-700' : 'text-gray-600'">Tutup
                                    </span>
                                </label>

                                <label
                                    class="relative flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-all duration-200"
                                    :class="statusTipe === 'jam_khusus' ? 'border-blue-500 bg-blue-50/50' :
                                        'border-gray-200 bg-gray-50 hover:bg-gray-100'">
                                    <input type="radio" name="status_tipe" value="jam_khusus" x-model="statusTipe"
                                        class="hidden">
                                    <span class="text-sm font-bold"
                                        :class="statusTipe === 'jam_khusus' ? 'text-blue-700' : 'text-gray-600'">Jam
                                        Khusus</span>
                                </label>
                            </div>
                        </div>

                        {{-- Input Jam Khusus --}}
                        <div x-show="statusTipe === 'jam_khusus'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="grid grid-cols-2 gap-4 pt-2">
                            <div>
                                <label for="open_time" class="block text-xs font-bold text-gray-700 mb-1">Jam Buka <span
                                        class="text-red-500">*</span></label>
                                <input type="time" name="open_time" id="open_time" x-model="openTime"
                                    class="w-full px-3 py-2 bg-white border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 text-sm"
                                    :class="showErrors && !openTime ? 'border-red-400 focus:border-red-400' :
                                        'border-gray-200 focus:border-blue-400'">
                                <p x-show="showErrors && !openTime"
                                    class="text-[10px] text-red-500 mt-1 font-medium leading-tight">Wajib diisi.</p>
                            </div>
                            <div>
                                <label for="close_time" class="block text-xs font-bold text-gray-700 mb-1">Jam Tutup
                                    <span class="text-red-500">*</span></label>
                                <input type="time" name="close_time" id="close_time" x-model="closeTime"
                                    class="w-full px-3 py-2 bg-white border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-100 text-sm"
                                    :class="showErrors && !closeTime ? 'border-red-400 focus:border-red-400' :
                                        'border-gray-200 focus:border-blue-400'">
                                <p x-show="showErrors && !closeTime"
                                    class="text-[10px] text-red-500 mt-1 font-medium leading-tight">Wajib diisi.</p>
                            </div>
                        </div>

                        {{-- Input Alasan --}}
                        <div>
                            <label for="reason" class="block text-sm font-bold text-gray-700 mb-2">Keterangan <span
                                    class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="reason" id="reason"
                                placeholder="Contoh: Libur Idul Fitri"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 text-sm transition-all duration-200">
                        </div>

                        <button type="submit"
                            class="w-full text-white font-bold py-3 px-4 rounded-xl transition duration-300 shadow-sm flex items-center justify-center gap-2 mt-2"
                            :class="statusTipe === 'tutup' ? 'bg-red-600 hover:bg-red-700 shadow-red-200' :
                                'bg-blue-600 hover:bg-blue-700 shadow-blue-200'">
                            Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabel Daftar Jadwal Khusus (Kolom Kanan) --}}
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Daftar Jadwal Khusus</h3>
                        <span
                            class="bg-gray-200 text-gray-700 py-1 px-3 rounded-full text-xs font-bold">{{ $schedules->count() }}
                            Jadwal</span>
                    </div>

                    <div class="overflow-x-auto hide-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[500px]">
                            <thead class="bg-gray-50">
                                <tr
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                    <th class="p-4 pl-6">Tanggal</th>
                                    <th class="p-4">Status & Keterangan</th>
                                    <th class="p-4 text-right pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse ($schedules as $schedule)
                                    <tr class="hover:bg-gray-50/50 transition duration-200 group">

                                        {{-- Kolom Tanggal --}}
                                        <td class="p-4 pl-6 whitespace-nowrap">
                                            <div class="font-bold text-gray-800">
                                                {{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('d F Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 capitalize">
                                                {{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('l') }}
                                            </div>
                                        </td>

                                        {{-- Kolom Status & Keterangan --}}
                                        <td class="p-4">
                                            @if ($schedule->is_closed)
                                                <span
                                                    class="inline-flex items-center gap-1.5 bg-red-50 border border-red-100 text-red-700 px-2.5 py-1 rounded-lg text-xs font-bold">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                        </path>
                                                    </svg>
                                                    Tutup
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-bold">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Buka:
                                                    {{ \Carbon\Carbon::parse($schedule->open_time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($schedule->close_time)->format('H:i') }}
                                                </span>
                                            @endif

                                            @if ($schedule->reason)
                                                <div class="text-sm text-gray-600 mt-1.5 font-medium">
                                                    {{ $schedule->reason }}</div>
                                            @endif
                                        </td>

                                        {{-- Kolom Aksi --}}
                                        <td class="p-4 text-right pr-6 whitespace-nowrap">
                                            {{-- Tombol untuk membuka Modal --}}
                                            <button type="button"
                                                @click="
                                                    openDeleteModal = true; 
                                                    deleteUrl = '{{ route('admin.schedules.destroy', $schedule->id) }}';
                                                    deleteDate = '{{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('d F Y') }}';
                                                "
                                                class="text-sm font-semibold text-gray-400 hover:text-red-600 transition-colors flex items-center justify-end ml-auto">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-10 text-center">
                                            <div
                                                class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-gray-50 mb-3 border border-gray-100">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-gray-900">Belum ada pengaturan jadwal
                                                khusus</p>
                                            <p class="text-sm text-gray-500 mt-1">Studio saat ini beroperasi setiap
                                                hari dengan jadwal reguler (11:00 - 18:00).</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- MODAL KONFIRMASI HAPUS JADWAL (Mirip Modal Logout) --}}
        <template x-teleport="body">
            <div x-show="openDeleteModal"
                class="fixed inset-0 z-[99] flex items-center justify-center min-h-screen px-4 py-6 sm:px-0"
                style="display: none;">

                {{-- Background Overlay --}}
                <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-70 backdrop-blur-sm"
                    @click="openDeleteModal = false"></div>

                {{-- Modal Box --}}
                <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full max-w-sm overflow-hidden transition-all transform bg-white rounded-2xl shadow-xl">

                    <div class="p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Buka Jadwal Kembali?</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Pengaturan jadwal khusus pada <br>
                            <span class="font-bold text-gray-800" x-text="deleteDate"></span> akan dihapus.
                        </p>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                        {{-- Form Hapus (Hidden) --}}
                        <form :action="deleteUrl" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:text-sm transition">
                                Ya, Lanjutkan
                            </button>
                        </form>

                        <button @click="openDeleteModal = false" type="button"
                            class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm transition">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>
