<x-app-layout>
    <div x-data="{
        expandedRow: null, // State untuk melacak baris mana yang sedang terbuka
        isModalOpen: false,
        actionUrl: '',
        actionMethod: 'POST',
        modalTitle: '',
        modalMessage: '',
        confirmText: '',
        confirmColor: '',
        iconColor: '',
    
        // --- VARIABEL UNTUK MODAL RESCHEDULE ---
        isRescheduleModalOpen: false,
        rescheduleUrl: '',
    
        openModal(type, url) {
            this.actionUrl = url;
            this.isModalOpen = true;
    
            if (type === 'delete') {
                this.actionMethod = 'DELETE';
                this.modalTitle = 'Hapus Transaksi?';
                this.modalMessage = 'Data yang dihapus tidak bisa dikembalikan.';
                this.confirmText = 'Ya, Hapus';
                this.confirmColor = 'bg-red-600 hover:bg-red-700';
                this.iconColor = 'text-red-600 bg-red-100';
            } else if (type === 'approve') {
                this.actionMethod = 'POST';
                this.modalTitle = 'Konfirmasi DP Manual';
                this.modalMessage = 'Setujui pembayaran DP ini secara manual?';
                this.confirmText = 'Ya, Setujui';
                this.confirmColor = 'bg-yellow-500 hover:bg-yellow-600';
                this.iconColor = 'text-yellow-600 bg-yellow-100';
            } else if (type === 'complete') {
                this.actionMethod = 'POST';
                this.modalTitle = 'Konfirmasi Pelunasan';
                this.modalMessage = 'Konfirmasi bahwa pelanggan sudah melunasi sisa tagihan di studio?';
                this.confirmText = 'Ya, Lunas';
                this.confirmColor = 'bg-blue-600 hover:bg-blue-700';
                this.iconColor = 'text-blue-600 bg-blue-100';
            }
        },
    
        // --- FUNGSI BUKA MODAL RESCHEDULE ---
        openRescheduleModal(url) {
            this.rescheduleUrl = url;
            this.isRescheduleModalOpen = true;
        }
    }">

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 min-h-[85vh] p-6 lg:p-8">

            {{-- HEADER BANNER --}}
            <div class="mb-6 bg-gradient-to-r from-red-50 to-white p-8 rounded-3xl border border-red-100/50">
                <div class="">
                    <h1 class="text-lg md:text-2xl font-extrabold text-gray-900 tracking-tight">
                        Daftar <span class="text-red-600">Transaksi</span>
                    </h1>
                    <p class="text-gray-500 mt-2 text-sm md:text-base">
                        Kelola daftar transaksi yang tersedia di studio.
                    </p>
                </div>
            </div>

            {{-- SEARCH BAR --}}
            <div class="mb-6">
                <form action="{{ route('admin.transactions.index') }}" method="GET"
                    class="relative flex items-center max-w-md">

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:ring-red-500 focus:border-red-500 shadow-sm text-sm transition-all duration-300"
                        placeholder="Cari Kode Booking atau Nama...">

                    @if (request('search'))
                        <a href="{{ route('admin.transactions.index') }}"
                            class="absolute inset-y-0 right-14 pr-3 flex items-center text-gray-400 hover:text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif

                    <button type="submit"
                        class="absolute right-1 top-1 bottom-1 px-4 bg-red-600 hover:bg-red-700 text-white rounded-full text-xs font-bold transition">
                        Cari
                    </button>
                </form>
            </div>

            @if (session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded-r-lg">
                    <p class="font-bold">Gagal!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded-r-lg">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- TRANSACTIONS ACCORDION LIST --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">

                {{-- TABLE HEADER (Hanya Desktop) --}}
                <div
                    class="hidden md:grid grid-cols-12 gap-4 p-5 bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <div class="col-span-3">Kode & Pelanggan</div>
                    <div class="col-span-4">Paket Foto</div>
                    <div class="col-span-3">Total Biaya</div>
                    <div class="col-span-2 text-right pr-8">Status</div>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse ($bookings as $booking)
                        <div class="group">
                            {{-- 1. BARIS UTAMA (Ringkasan) --}}
                            <div class="cursor-pointer hover:bg-gray-50/80 transition-colors duration-200 p-4 md:p-5"
                                @click="expandedRow === {{ $booking->id }} ? expandedRow = null : expandedRow = {{ $booking->id }}">

                                <div class="flex items-center justify-between md:grid md:grid-cols-12 md:gap-4">

                                    {{-- Kolom 1: Kode & Customer --}}
                                    <div class="md:col-span-3 flex flex-col">
                                        <span
                                            class="font-bold text-gray-900 text-sm tracking-wide">{{ $booking->booking_code }}</span>
                                        <span
                                            class="text-xs text-gray-600 font-medium mt-0.5">{{ $booking->user->name }}</span>
                                        {{-- Mobile Info Bantuan --}}
                                        <span
                                            class="text-[10px] text-gray-400 mt-0.5 md:hidden">{{ $booking->package->name }}</span>
                                    </div>

                                    {{-- Kolom 2: Paket (Hanya Desktop) --}}
                                    <div class="hidden md:block md:col-span-4 font-semibold text-gray-800 text-sm">
                                        {{ $booking->package->name }}
                                        <span class="block text-xs font-normal text-gray-500 mt-0.5">
                                            {{ $booking->start_time->timezone('Asia/Jakarta')->format('d M Y') }}
                                        </span>
                                    </div>

                                    {{-- Kolom 3: Total Harga (Hanya Desktop) --}}
                                    <div class="hidden md:block md:col-span-3 font-bold text-gray-900 text-sm">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </div>

                                    {{-- Kolom 4: Status & Chevron --}}
                                    <div class="md:col-span-2 flex items-center justify-end gap-3 md:gap-4">
                                        {{-- Status Badge --}}
                                        <div>
                                            @if ($booking->status == 'paid' && $booking->remaining_balance == 0)
                                                <span
                                                    class="bg-green-100 text-green-700 px-2.5 py-1 rounded-md text-[10px] font-bold border border-green-200 uppercase">Lunas</span>
                                            @elseif($booking->status == 'paid' && $booking->remaining_balance > 0)
                                                <span
                                                    class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-md text-[10px] font-bold border border-blue-200 uppercase">Sudah
                                                    DP</span>
                                            @elseif($booking->status == 'unpaid')
                                                <span
                                                    class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-md text-[10px] font-bold border border-yellow-200 uppercase">Belum
                                                    DP</span>
                                            @else
                                                <span
                                                    class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-[10px] font-bold border border-red-200 uppercase">{{ $booking->status }}</span>
                                            @endif
                                        </div>

                                        {{-- Ikon Chevron --}}
                                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-300"
                                            :class="expandedRow === {{ $booking->id }} ? 'rotate-180' : ''"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. PANEL DETAIL --}}
                            <div x-show="expandedRow === {{ $booking->id }}"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="bg-gray-50/50 border-t border-gray-100 px-4 py-5 md:px-6 md:py-6"
                                style="display: none;">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    {{-- Kolom Kiri: Detail Informasi --}}
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p
                                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                    Jadwal Foto</p>
                                                <p class="text-sm font-bold text-gray-800">
                                                    {{ $booking->start_time->timezone('Asia/Jakarta')->format('d M Y') }}
                                                </p>
                                            </div>

                                            <div>
                                                <p
                                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                    Informasi Kontak</p>
                                                <p
                                                    class="text-sm font-semibold text-gray-800 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                        </path>
                                                    </svg>
                                                    {{ $booking->user->phone ?? 'Tidak ada No. HP' }}
                                                </p>
                                            </div>

                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p
                                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                    Jam Foto (WIB)</p>
                                                <p class="text-sm font-bold text-gray-800">
                                                    {{ $booking->start_time->timezone('Asia/Jakarta')->format('H:i') }}
                                                    - {{ $booking->end_time->timezone('Asia/Jakarta')->format('H:i') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p
                                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                    Jumlah Orang</p>
                                                <p
                                                    class="text-sm font-semibold text-gray-800 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </path>
                                                    </svg>
                                                    {{ $booking->total_pax ?? 1 }} Orang
                                                </p>
                                            </div>
                                        </div>

                                        <div class="bg-white rounded-xl p-3 border border-gray-100 shadow-sm mt-2">
                                            <div class="flex justify-between items-center text-xs mb-1">
                                                <span class="text-gray-500">Total Biaya</span>
                                                <span class="font-bold text-gray-800">Rp
                                                    {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs mb-2">
                                                <span class="text-gray-500">Uang Muka (DP)</span>
                                                <span class="font-bold text-gray-800">Rp
                                                    {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                                            </div>
                                            <div
                                                class="border-t border-gray-100 pt-2 flex justify-between items-center text-sm">
                                                <span class="font-bold text-gray-700">Sisa Pelunasan</span>
                                                <span
                                                    class="font-extrabold {{ $booking->remaining_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                    Rp {{ number_format($booking->remaining_balance, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Kolom Kanan: Hasil Foto & Aksi --}}
                                    <div
                                        class="space-y-5 flex flex-col justify-between border-t border-gray-200 pt-5 md:border-t-0 md:pt-0 md:pl-4 md:border-l">

                                        {{-- Link Hasil Foto --}}
                                        <div>
                                            <p
                                                class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                Link Google Drive Hasil Foto</p>
                                            <form action="{{ route('admin.transactions.drive', $booking->id) }}"
                                                method="POST" class="flex gap-2">
                                                @csrf
                                                <input type="url" name="google_drive_link"
                                                    value="{{ $booking->google_drive_link }}"
                                                    placeholder="https://drive.google.com/..."
                                                    class="w-full text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                                    required>
                                                <button type="submit"
                                                    class="bg-gray-800 text-white px-3 py-2 rounded-lg hover:bg-gray-700 transition shadow-sm text-xs font-bold"
                                                    title="Simpan Link">
                                                    Simpan
                                                </button>
                                            </form>
                                            @if ($booking->google_drive_link)
                                                <div class="mt-2">
                                                    <a href="{{ $booking->google_drive_link }}" target="_blank"
                                                        class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors w-max">
                                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M20.1 10.3h-8v3.4h4.6c-.2 1.5-1.1 2.8-2.4 3.6l3.8 3c2.2-2.1 3.5-5.2 3.5-8.8 0-.6-.1-1.2-.2-1.8zM12 21c2.5 0 4.6-.8 6.1-2.2l-3.8-3c-.8.5-1.8.8-2.3.8-2.6 0-4.8-1.7-5.5-4h-3.9v3.1C4.1 18.9 7.7 21 12 21zm-5.5-8c-.2-.6-.2-1.2-.2-1.8 0-.6.1-1.2.2-1.8V6.3H2.6C1.8 7.8 1.4 9.4 1.4 11c0 1.6.4 3.2 1.2 4.7l3.9-3zM12 3c1.3 0 2.5.5 3.5 1.3l2.6-2.6C16.6.6 14.5 0 12 0 7.7 0 4.1 2.1 2.6 5.3l3.9 3c.7-2.3 2.9-4 5.5-4z" />
                                                        </svg>
                                                        Buka Folder Drive
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Tombol Aksi --}}
                                        <div>
                                            <p
                                                class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                Aksi Manajemen</p>
                                            <div class="flex flex-wrap items-center gap-2">

                                                @if ($booking->status == 'unpaid')
                                                    <button
                                                        @click="openModal('approve', '{{ route('admin.transactions.approve', $booking->id) }}')"
                                                        type="button"
                                                        class="bg-yellow-500 text-white px-3 py-2 rounded-lg hover:bg-yellow-600 transition shadow-sm text-xs font-bold flex items-center gap-1.5"
                                                        title="Approve DP Manual">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Approve Manual
                                                    </button>
                                                @endif

                                                @if ($booking->status == 'paid' && $booking->remaining_balance > 0)
                                                    <button
                                                        @click="openModal('complete', '{{ route('admin.transactions.complete', $booking->id) }}')"
                                                        type="button"
                                                        class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm text-xs font-bold flex items-center gap-1.5"
                                                        title="Konfirmasi Pelunasan">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                        Konfirmasi Lunas
                                                    </button>

                                                    {{-- Tombol Reschedule HANYA muncul jika belum lunas --}}
                                                    <button
                                                        @click="openRescheduleModal('{{ route('admin.bookings.reschedule', $booking->id) }}')"
                                                        type="button"
                                                        class="bg-purple-600 text-white px-3 py-2 rounded-lg hover:bg-purple-700 transition shadow-sm text-xs font-bold flex items-center gap-1.5"
                                                        title="Reschedule Jadwal">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 12v-3m0 0v3m0 0l3 3m-3-3l-3 3">
                                                            </path>
                                                        </svg>
                                                        Reschedule
                                                    </button>
                                                @endif

                                                <button
                                                    @click="openModal('delete', '{{ route('admin.transactions.destroy', $booking->id) }}')"
                                                    type="button"
                                                    class="bg-white border border-gray-200 text-red-600 hover:bg-red-50 hover:border-red-200 px-3 py-2 rounded-lg transition shadow-sm text-xs font-bold flex items-center gap-1.5"
                                                    title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-500 font-medium">
                            @if (request('search'))
                                Tidak menemukan transaksi dengan kata kunci "{{ request('search') }}".
                            @else
                                Belum ada data transaksi.
                            @endif
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION LARAVEL --}}
                @if ($bookings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $bookings->appends(['search' => request('search')])->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL KONFIRMASI UMUM --}}
        <template x-teleport="body">
            <div x-show="isModalOpen"
                class="fixed inset-0 z-[99] flex items-center justify-center min-h-screen px-4 py-6 sm:px-0"
                style="display: none;">
                <div x-show="isModalOpen" x-transition.opacity
                    class="fixed inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm" @click="isModalOpen = false">
                </div>
                <div x-show="isModalOpen" x-transition
                    class="relative w-full max-w-sm overflow-hidden transition-all transform bg-white rounded-2xl shadow-xl">
                    <div class="p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4"
                            :class="iconColor">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900" x-text="modalTitle"></h3>
                        <p class="mt-2 text-sm text-gray-500" x-text="modalMessage"></p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                        <form :action="actionUrl" method="POST" class="w-full">
                            @csrf
                            <template x-if="actionMethod === 'DELETE'">
                                <input type="hidden" name="_method" value="DELETE">
                            </template>
                            <button type="submit" :class="confirmColor"
                                class="w-full inline-flex justify-center rounded-lg shadow-sm px-4 py-2 font-medium text-white transition"
                                x-text="confirmText"></button>
                        </form>
                        <button @click="isModalOpen = false" type="button"
                            class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    </div>
                </div>
            </div>
        </template>

        {{-- MODAL RESCHEDULE --}}
        <template x-teleport="body">
            <div x-show="isRescheduleModalOpen"
                class="fixed inset-0 z-[99] flex items-center justify-center min-h-screen px-4 py-6 sm:px-0"
                style="display: none;">
                <div x-show="isRescheduleModalOpen" x-transition.opacity
                    class="fixed inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm"
                    @click="isRescheduleModalOpen = false"></div>
                <div x-show="isRescheduleModalOpen" x-transition
                    class="relative w-full max-w-md overflow-hidden transition-all transform bg-white rounded-2xl shadow-xl">
                    <form :action="rescheduleUrl" method="POST">
                        @csrf
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-4">
                                <div class="bg-purple-100 text-purple-600 p-2 rounded-full">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Reschedule Jadwal</h3>
                                    <p class="text-sm text-gray-500">Pindahkan jadwal pelanggan ke waktu baru.</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Tanggal
                                        Baru</label>
                                    <input type="date" name="date" required
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Jam
                                        Baru</label>
                                    <input type="time" name="time" required
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                    <span class="text-xs text-gray-500 mt-1 block">*Sistem otomatis akan mengecek jika
                                        jam ini bertabrakan.</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <button @click="isRescheduleModalOpen = false" type="button"
                                class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                            <button type="submit"
                                class="inline-flex justify-center rounded-lg shadow-sm px-4 py-2 bg-purple-600 hover:bg-purple-700 text-sm font-medium text-white transition">Simpan
                                Jadwal Baru</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
