<x-frontend-layout>
    <div class="min-h-screen bg-gray-50 pb-12">

        {{-- Header Banner --}}
        <div class="relative bg-red-700 pt-16 pb-24 overflow-hidden shadow-md">
            <div class="relative z-10 max-w-7xl text-center uppercase mx-auto px-4 sm:px-6 lg:px-8 items-center">
                <h1 class="font-extrabold text-3xl md:text-4xl text-white tracking-tighter drop-shadow-sm">
                    Riwayat Transaksi
                </h1>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20" x-data="{ expandedRow: null }">

            @if ($bookings->isEmpty())
                <div class="bg-white overflow-hidden shadow-md rounded-2xl lg:rounded-3xl border border-gray-100">
                    <div class="text-center py-20 px-4">
                        <div class="bg-red-50 rounded-full p-6 inline-flex mb-5">
                            <svg class="w-14 h-14 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800">Belum ada riwayat booking</h3>
                        <p class="text-gray-500 mt-2 text-sm md:text-base max-w-md mx-auto">
                            Wah kayanya kamu belum pernah foto di Alineas Studio.
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-md rounded-2xl lg:rounded-3xl border border-gray-200">

                    {{-- HEADER TABEL (Desktop) — 3 kolom sejajar dengan baris isi --}}
                    <div
                        class="hidden md:grid md:grid-cols-3 gap-4 p-5 bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <div>Kode Booking</div>
                        <div>Paket Foto</div>
                        <div class="text-right pr-10">Status</div>
                    </div>

                    {{-- DAFTAR TRANSAKSI --}}
                    <div class="divide-y divide-gray-200">
                        @foreach ($bookings as $booking)
                            @php
                                $isExpired = false;
                                if ($booking->status == 'unpaid') {
                                    $timePassed = now()->diffInMinutes($booking->created_at);
                                    if ($timePassed > 15) {
                                        $isExpired = true;
                                    }
                                }
                            @endphp

                            <div class="group">
                                {{-- BARIS RINGKASAN --}}
                                <div class="cursor-pointer hover:bg-gray-50 transition-colors duration-200 p-4 md:p-5"
                                    @click="expandedRow === {{ $booking->id }} ? expandedRow = null : expandedRow = {{ $booking->id }}">

                                    {{-- Mobile: flex biasa. Desktop: 3 kolom sejajar header --}}
                                    <div
                                        class="flex items-center justify-between md:grid md:grid-cols-3 md:gap-4 md:items-center">

                                        {{-- Kolom 1: Tanggal Foto --}}
                                        <div class="flex flex-col">
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ $booking->booking_code }}</p>
                                            {{-- Nama paket hanya muncul di mobile, di bawah tanggal --}}
                                            <span class="text-xs md:hidden text-gray-500 mt-0.5">
                                                {{ Str::limit($booking->package->name, 25) }}
                                            </span>
                                        </div>

                                        {{-- Kolom 2: Nama Paket (Desktop saja) --}}
                                        <div class="hidden md:block font-semibold text-gray-800 text-sm">
                                            {{ $booking->package->name }}
                                        </div>

                                        {{-- Kolom 3: Status & Chevron --}}
                                        <div class="flex items-center justify-end gap-3">
                                            {{-- Badge Status --}}
                                            @if ($isExpired)
                                                <span
                                                    class="text-red-600 bg-red-50 border border-red-100 px-2 py-1 rounded text-[10px] font-bold uppercase">Batal</span>
                                            @elseif ($booking->status == 'paid' && $booking->remaining_balance == 0)
                                                <span
                                                    class="text-green-700 bg-green-50 border border-green-200 px-2 py-1 rounded text-[10px] font-bold uppercase">Lunas</span>
                                            @elseif ($booking->status == 'paid' && $booking->remaining_balance > 0)
                                                <span
                                                    class="text-blue-700 bg-blue-50 border border-blue-200 px-2 py-1 rounded text-[10px] font-bold uppercase">DP</span>
                                            @elseif ($booking->status == 'unpaid')
                                                <span
                                                    class="text-yellow-700 bg-yellow-50 border border-yellow-200 px-2 py-1 rounded text-[10px] font-bold uppercase flex items-center gap-1">
                                                    <svg class="w-2.5 h-2.5 animate-spin" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    Menunggu
                                                </span>
                                            @endif

                                            {{-- Chevron --}}
                                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-300"
                                                :class="expandedRow === {{ $booking->id }} ? 'rotate-180' : ''"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- PANEL DETAIL --}}
                                <div x-show="expandedRow === {{ $booking->id }}"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="bg-gray-50/50 border-t border-gray-100 px-4 py-5 md:px-8 md:py-6"
                                    style="display: none;">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">

                                        {{-- SISI KIRI: Info Booking & Keuangan --}}
                                        <div class="space-y-5">

                                            {{-- Info Booking --}}
                                            <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                                                <div>
                                                    <p
                                                        class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                        Jadwal Foto</p>
                                                    <p class="text-sm font-semibold text-gray-800">
                                                        {{ $booking->start_time->locale('id')->isoFormat('D MMM Y') }}</p>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                        Jumlah Orang</p>
                                                    <p class="text-sm font-semibold text-gray-800">
                                                        {{ $booking->total_pax ?? 1 }} Orang</p>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                        Jam Foto (WIB)</p>
                                                    <p class="text-sm font-semibold text-gray-800">
                                                        {{ $booking->start_time_wib }} WIB</p>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                        Harga Total</p>
                                                    <p class="text-sm font-semibold text-gray-800">Rp
                                                        {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                                </div>
                                            </div>

                                            {{-- Rincian Keuangan --}}
                                            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
                                                <div class="flex justify-between items-center text-sm px-4 py-3">
                                                    <span class="text-gray-500">Uang Muka (DP)</span>
                                                    <span class="font-semibold text-gray-800">Rp
                                                        {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                                                </div>

                                                @if ($booking->remaining_balance > 0 && !$isExpired)
                                                    <div
                                                        class="flex justify-between items-center text-sm px-4 py-3 border-t border-gray-100 bg-gray-50">
                                                        <span class="font-semibold text-gray-700">Sisa Pelunasan</span>
                                                        <span class="font-bold text-gray-900">Rp
                                                            {{ number_format($booking->remaining_balance, 0, ',', '.') }}</span>
                                                    </div>
                                                    @if ($booking->status == 'paid')
                                                        <p class="text-[10px] text-gray-400 italic px-4 pb-3 pt-1">
                                                            *Silakan lunasi sisa tagihan saat tiba di studio.</p>
                                                    @endif
                                                @endif
                                            </div>

                                        </div>

                                        {{-- SISI KANAN: Hasil Foto & Aksi --}}
                                        <div class="flex flex-col justify-between gap-5 md:border-l md:pl-8 border-gray-200">

                                            {{-- Link Drive --}}
                                            <div>
                                                <p
                                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                    Link Hasil Foto</p>
                                                @if ($booking->google_drive_link)
                                                    <a href="{{ $booking->google_drive_link }}" target="_blank"
                                                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-xs font-semibold bg-blue-50 border border-blue-100 hover:bg-blue-100 px-3 py-2 rounded-lg transition-colors w-fit">
                                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M20.1 10.3h-8v3.4h4.6c-.2 1.5-1.1 2.8-2.4 3.6l3.8 3c2.2-2.1 3.5-5.2 3.5-8.8 0-.6-.1-1.2-.2-1.8zM12 21c2.5 0 4.6-.8 6.1-2.2l-3.8-3c-.8.5-1.8.8-2.3.8-2.6 0-4.8-1.7-5.5-4h-3.9v3.1C4.1 18.9 7.7 21 12 21zm-5.5-8c-.2-.6-.2-1.2-.2-1.8 0-.6.1-1.2.2-1.8V6.3H2.6C1.8 7.8 1.4 9.4 1.4 11c0 1.6.4 3.2 1.2 4.7l3.9-3zM12 3c1.3 0 2.5.5 3.5 1.3l2.6-2.6C16.6.6 14.5 0 12 0 7.7 0 4.1 2.1 2.6 5.3l3.9 3c.7-2.3 2.9-4 5.5-4z" />
                                                        </svg>
                                                        Akses Google Drive
                                                    </a>
                                                @else
                                                    <span
                                                        class="text-xs text-gray-400 font-medium bg-white px-3 py-1.5 rounded-md border border-gray-200">
                                                        Belum tersedia saat ini.
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Tombol Aksi --}}
                                            <div class="pt-4 border-t border-gray-200 md:pt-0 md:border-none">
                                                @if ($isExpired)
                                                    <span
                                                        class="block w-full text-center text-gray-500 text-xs font-bold bg-gray-100 px-4 py-3 rounded-xl border border-gray-200 cursor-not-allowed">
                                                        Pesanan Dibatalkan
                                                    </span>
                                                @elseif ($booking->status == 'unpaid')
                                                    <a href="{{ route('booking.payment', $booking->id) }}"
                                                        class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold text-sm px-5 py-3 rounded-xl transition shadow-sm shadow-red-200">
                                                        Lanjutkan Pembayaran DP
                                                    </a>
                                                @elseif ($booking->status == 'paid')
                                                    @if ($booking->remaining_balance > 0)
                                                        @php
                                                            $waNumber = '6285213385280';
                                                            $waMessage = urlencode(
                                                                "Halo Min Al, Saya ingin mengajukan reschedule jadwal foto untuk kode booking *{$booking->booking_code}*.",
                                                            );
                                                        @endphp
                                                        <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                                                            target="_blank"
                                                            class="flex items-center justify-center gap-2 w-full bg-white border border-green-600 hover:bg-green-100 text-green-600 font-bold text-sm px-5 py-3 rounded-xl transition shadow-sm">
                                                            Ajukan Reschedule
                                                        </a>
                                                    @else
                                                        <span
                                                            class="flex items-center justify-center gap-1.5 w-full bg-green-50 border border-green-200 text-green-700 text-sm font-bold px-4 py-3 rounded-xl cursor-default">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Transaksi Selesai
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-frontend-layout>
