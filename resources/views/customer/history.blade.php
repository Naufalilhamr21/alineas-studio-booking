<x-frontend-layout>
    <div class="min-h-screen bg-gray-50 pb-12">

        <div class="relative bg-red-700 pt-16 pb-24 overflow-hidden shadow-md">
            <div class="relative z-10 max-w-7xl text-center uppercase mx-auto px-4 sm:px-6 lg:px-8 items-center">
                <h1 class="font-extrabold text-3xl md:text-4xl text-white tracking-tighter drop-shadow-sm">
                    Riwayat Transaksi
                </h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto lg:mx-8 px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
            <div class="bg-white overflow-hidden shadow-md rounded-2xl lg:rounded-3xl border border-gray-100">
                <div class="p-4 md:p-6 text-gray-900">

                    @if ($bookings->isEmpty())
                        <div class="text-center py-20 px-4">
                            <div class="bg-red-50 rounded-full p-6 inline-flex mb-5">
                                <svg class="w-14 h-14 text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-extrabold text-gray-800">Belum ada riwayat booking</h3>
                            <p class="text-gray-500 mt-2 text-sm md:text-base max-w-md mx-auto">
                                Kamu belum pernah melakukan pemesanan. Yuk, lihat daftar harga dan booking sesi foto
                                pertamamu di Alineas Studio!
                            </p>
                            <a href="{{ route('pricelist') }}"
                                class="inline-block mt-6 px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold rounded-full text-sm transition shadow-md hover:shadow-lg">
                                Lihat Pricelist
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto hide-scrollbar">
                            <table class="w-full text-left border-collapse min-w-[900px]">
                                <thead>
                                    <tr
                                        class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                        <th class="pb-4 pl-4">Paket & Jadwal</th>
                                        <th class="pb-4">Keuangan</th>
                                        <th class="pb-4">Status Transaksi</th>
                                        <th class="pb-4">Hasil Foto</th>
                                        <th class="pb-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @foreach ($bookings as $booking)
                                        @php
                                            // LOGIKA KADALUARSA (EXPIRED)
                                            $isExpired = false;
                                            if ($booking->status == 'unpaid') {
                                                $timePassed = now()->diffInMinutes($booking->created_at);
                                                if ($timePassed > 15) {
                                                    $isExpired = true;
                                                }
                                            }
                                        @endphp

                                        <tr class="hover:bg-gray-50 transition duration-200">
                                            <td class="py-5 pl-4">
                                                <div class="flex items-center gap-4">
                                                    <img src="{{ asset('storage/' . $booking->package->thumbnail) }}"
                                                        class="w-16 h-16 rounded-xl object-cover bg-gray-200 border border-gray-100 shadow-sm">
                                                    <div>
                                                        <p class="font-bold text-gray-900 text-base mb-0.5">
                                                            {{ $booking->package->name }}
                                                        </p>
                                                        <p class="text-xs text-gray-400 mb-2">Kode: <span
                                                                class="font-semibold text-gray-600">{{ $booking->booking_code }}</span>
                                                        </p>
                                                        <div
                                                            class="inline-flex items-center gap-1.5 text-xs text-gray-700 bg-gray-100 px-2.5 py-1 rounded-md font-medium">
                                                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            {{ $booking->start_time->locale('id')->isoFormat('D MMM Y') }}
                                                            • {{ $booking->start_time_wib }} WIB
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-5">
                                                <div class="font-bold text-gray-900 text-sm mb-1.5">
                                                    Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                                </div>
                                                <div class="text-xs text-gray-500 mb-1">
                                                    DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                                </div>

                                                @if ($booking->remaining_balance > 0)
                                                    <div class="text-xs text-red-600 font-bold">
                                                        Sisa: Rp
                                                        {{ number_format($booking->remaining_balance, 0, ',', '.') }}
                                                    </div>
                                                    @if ($booking->status == 'paid')
                                                        <div
                                                            class="text-[11px] text-gray-500 mt-1 leading-tight italic">
                                                            *Silakan lunasi di studio
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="text-xs text-green-600 font-bold">Sisa: Rp 0</div>
                                                @endif
                                            </td>

                                            <td class="py-5">
                                                @if ($isExpired)
                                                    <span
                                                        class="bg-red-50 text-red-700 px-3 py-1.5 rounded-full text-xs font-bold border border-red-200 inline-flex items-center gap-1.5 w-max">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        KADALUARSA
                                                    </span>
                                                @elseif ($booking->status == 'paid' && $booking->remaining_balance == 0)
                                                    <span
                                                        class="bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-xs font-bold border border-green-200 inline-flex items-center gap-1.5 w-max">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        LUNAS
                                                    </span>
                                                @elseif($booking->status == 'paid' && $booking->remaining_balance > 0)
                                                    <span
                                                        class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-full text-xs font-bold border border-blue-200 inline-flex items-center gap-1.5 w-max">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                        DP DIBAYAR
                                                    </span>
                                                @elseif($booking->status == 'unpaid')
                                                    <span
                                                        class="bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-full text-xs font-bold border border-yellow-200 inline-flex items-center gap-1.5 w-max">
                                                        <svg class="w-3.5 h-3.5 animate-spin"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                        MENUNGGU DP
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="py-5">
                                                @if ($booking->google_drive_link)
                                                    <a href="{{ $booking->google_drive_link }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 hover:underline text-xs font-medium break-all max-w-[180px] inline-block">
                                                        {{ $booking->google_drive_link }}
                                                    </a>
                                                @else
                                                    <span
                                                        class="text-xs text-gray-400 font-medium bg-gray-50 px-2.5 py-1 rounded border border-gray-100">Belum
                                                        tersedia</span>
                                                @endif
                                            </td>

                                            <td
                                                class="py-5">
                                                @if ($isExpired)
                                                    <span class="text-gray-400 text-xs font-medium">Batal</span>
                                                @elseif ($booking->status == 'unpaid')
                                                    <a href="{{ route('booking.payment', $booking->id) }}"
                                                        class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-5 py-2.5 rounded-full transition shadow-sm inline-block transform hover:-translate-y-0.5 w-full max-w-[120px]">
                                                        Bayar DP
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
                                                            class="bg-green-500 hover:bg-green-600 text-white font-semibold text-[11px] px-3 py-1.5 rounded-lg transition duration-200 shadow-sm w-full max-w-[120px]">
                                                            Reschedule
                                                        </a>
                                                    @else
                                                        <span
                                                            class="text-gray-700 text-xs font-bold flex items-center justify-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Selesai
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>
