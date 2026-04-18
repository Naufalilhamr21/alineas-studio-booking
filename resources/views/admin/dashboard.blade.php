<x-app-layout>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 min-h-[85vh] p-6 lg:p-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tighter">
                Halo, <span class="text-red-600">{{ explode(' ', Auth::user()->name)[0] }}!</span>
            </h1>
        </div>

        {{-- Statistik Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            {{-- Total Transaksi --}}
            <div class="bg-red-600 p-6 rounded-3xl text-white shadow-sm">
                <p class="text-sm font-bold">Total Transaksi Bulan Ini</p>
                <h2 class="text-3xl font-extrabold  mt-2">
                    {{ $transaksiBulanIni }}
                </h2>
            </div>

            {{-- Total Pendapatan Bulan Ini --}}
            <div class="bg-red-600 p-6 rounded-3xl text-white shadow-sm">
                <p class="text-sm font-bold">Pendapatan Bulan Ini</p>
                <h2 class="text-3xl font-extrabold mt-2">
                    Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                </h2>
            </div>

            {{-- Total Pendapatan Keseluruhan --}}
            <div class="bg-red-600 p-6 rounded-3xl shadow-sm text-white">
                <p class="text-sm font-bold">Total Pendapatan Keseluruhan</p>
                <h2 class="text-3xl font-extrabold mt-2">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h2>
            </div>

        </div>

        {{-- Jadwal Mendatang --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    Jadwal Foto Mendatang
                </h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                <div class="overflow-x-auto hide-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-red-600">
                            <tr class="text-sm font-bold text-white tracking-tight border-b">
                                <th class="p-4 pl-12">Waktu</th>
                                <th class="p-4">Customer & Paket</th>
                                <th class="p-4 text-right pr-12">Status Bayar</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($upcomingBookings as $booking)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- WAKTU --}}
                                    <td class="p-4 pl-12 whitespace-nowrap">
                                        @if (\Carbon\Carbon::parse($booking->start_time)->isToday())
                                            <span
                                                class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-[10px] font-bold">
                                                HARI INI
                                            </span><br>
                                        @endif

                                        <div class="font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->locale('id')->isoFormat('D MMM YYYY') }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                                        </div>
                                    </td>

                                    {{-- CUSTOMER --}}
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $booking->package->name }}</div>
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="p-4 pr-12 text-right">
                                        @if ($booking->remaining_balance <= 0)
                                            <span
                                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                                LUNAS
                                            </span>
                                        @else
                                            <div class="flex flex-col items-end">
                                                <span
                                                    class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                                    DP
                                                </span>
                                                <span class="text-[11px] text-red-600 font-bold">
                                                    Sisa: Rp
                                                    {{ number_format($booking->remaining_balance, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-gray-500">
                                        Tidak ada jadwal foto mendatang
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
