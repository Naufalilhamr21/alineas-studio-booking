<x-app-layout>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 min-h-[85vh] p-6 lg:p-8">

        <div class="mb-10 bg-gradient-to-r from-red-50 to-white p-8 rounded-3xl border border-red-100/50">
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                Halo, <span class="text-red-600">{{ explode(' ', Auth::user()->name)[0] }}!</span>
            </h1>
            <p class="text-gray-500 mt-2 text-base">Selamat datang di pusat kendali Alineas Studio. Apa yang ingin Anda
                kelola hari ini?</p>
        </div>

        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                </path>
            </svg>
            Akses Cepat
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <a href="{{ route('admin.packages.index') }}"
                class="group block relative bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md hover:border-red-200 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-red-50 to-transparent rounded-full z-0 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 flex flex-col gap-4">
                    <div
                        class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-1">Paket & Harga</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Kelola daftar paket foto dan atur harga foto
                            studio.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.galleries.index') }}"
                class="group block relative bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md hover:border-emerald-200 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-emerald-50 to-transparent rounded-full z-0 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 flex flex-col gap-4">
                    <div
                        class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-1">Galeri Foto</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Unggah hasil jepretan terbaik untuk menarik
                            lebih banyak pelanggan.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.transactions.index') }}"
                class="group block relative bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-blue-50 to-transparent rounded-full z-0 group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10 flex flex-col gap-4">
                    <div
                        class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-1">Data Transaksi</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Pantau jadwal booking masuk, verifikasi DP, dan
                            pelunasan foto studio.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Jadwal Foto Mendatang
            </h2>
            <a href="{{ route('admin.transactions.index') }}"
                class="text-sm text-red-600 hover:text-red-800 font-medium transition-colors">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
            <div class="overflow-x-auto hide-scrollbar">
                <table class="w-full min-w-[700px] text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            <th class="p-4">Waktu</th>
                            <th class="p-4">Customer</th>
                            <th class="p-4">Paket</th>
                            <th class="p-4">Status Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($upcomingBookings as $booking)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 whitespace-nowrap">
                                    @php
                                        // Menentukan warna badge jika jadwalnya hari ini
                                        $isToday = \Carbon\Carbon::parse($booking->start_time)->isToday();
                                    @endphp

                                    @if ($isToday)
                                        <span
                                            class="inline-block mb-1 bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] font-bold tracking-wide">HARI
                                            INI</span><br>
                                    @endif

                                    <div class="font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM YYYY') }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 mt-1 font-medium bg-gray-100 px-2 py-1 rounded inline-block">
                                        ⏰
                                        {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Jakarta')->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($booking->end_time)->timezone('Asia/Jakarta')->format('H:i') }}
                                        WIB
                                    </div>
                                </td>

                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        {{ $booking->user->phone ?? '-' }}
                                    </div>
                                </td>

                                <td class="p-4">
                                    <div class="font-medium text-gray-700">{{ $booking->package->name }}</div>
                                </td>

                                <td class="p-4">
                                    @if ($booking->remaining_balance == 0)
                                        <span
                                            class="bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-[11px] font-bold border border-green-200 inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            LUNAS
                                        </span>
                                    @else
                                        <div class="flex flex-col gap-1 items-start">
                                            <span
                                                class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[11px] font-bold border border-blue-200">SUDAH
                                                DP</span>
                                            <span class="text-[10px] text-red-600 font-bold ml-1">Sisa: Rp
                                                {{ number_format($booking->remaining_balance, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center">
                                    <div
                                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-3">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">Tidak ada jadwal foto mendatang.</p>
                                    <p class="text-sm text-gray-500 mt-1">Belum ada pelanggan yang menyelesaikan
                                        pembayaran DP.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
