<x-app-layout>
    <div x-data="{
        isModalOpen: false,
        actionUrl: '',
        actionMethod: 'POST',
        modalTitle: '',
        modalMessage: '',
        confirmText: '',
        confirmColor: '',
        iconColor: '',
    
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
        }
    }">

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 min-h-[85vh] p-6 lg:p-8">

            <div
                class="flex justify-between items-center mb-6 bg-gradient-to-r from-red-50 to-white p-8 rounded-3xl border border-red-100/50">
                <div class="">
                    <h1 class="text-lg md:text-2xl font-extrabold text-gray-900 tracking-tight">
                        Daftar <span class="text-red-600">Transaksi</span>
                    </h1>
                    <p class="text-gray-500 mt-2 text-sm md:text-base">
                        Kelola daftar transaksi yang tersedia di studio.
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                <div class="overflow-x-auto hide-scrollbar">
                    <table class="w-full min-w-[1000px] text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr
                                class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                <th class="p-4">Kode & Customer</th>
                                <th class="p-4">Paket & Jadwal</th>
                                <th class="p-4">Keuangan</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Hasil Foto (G-Drive)</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4">
                                        <span class="block font-bold text-gray-800">{{ $booking->booking_code }}</span>
                                        <span
                                            class="text-xs text-gray-600 font-medium">{{ $booking->user->name }}</span>
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
                                        <div class="font-bold text-gray-700">{{ $booking->package->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $booking->start_time->timezone('Asia/Jakarta')->format('d M Y') }}<br>
                                            {{ $booking->start_time->timezone('Asia/Jakarta')->format('H:i') }}
                                            -
                                            {{ $booking->end_time->timezone('Asia/Jakarta')->format('H:i') }}
                                        </div>
                                    </td>

                                    <td class="p-4">
                                        <div class="font-bold text-gray-800 text-sm">
                                            Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                        </div>
                                        @if ($booking->remaining_balance > 0)
                                            <div class="text-xs text-red-600 font-bold mt-0.5">Sisa: Rp
                                                {{ number_format($booking->remaining_balance, 0, ',', '.') }}</div>
                                        @else
                                            <div class="text-xs text-green-600 font-bold mt-0.5">Sisa: Rp 0</div>
                                        @endif
                                    </td>

                                    <td class="p-4">
                                        @if ($booking->status == 'paid' && $booking->remaining_balance == 0)
                                            <span
                                                class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-xs font-bold border border-green-200 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                LUNAS
                                            </span>
                                        @elseif($booking->status == 'paid' && $booking->remaining_balance > 0)
                                            <span
                                                class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-200">SUDAH DP</span>
                                        @elseif($booking->status == 'unpaid')
                                            <span
                                                class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold border border-yellow-200">BELUM
                                                DP</span>
                                        @else
                                            <span
                                                class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">{{ strtoupper($booking->status) }}</span>
                                        @endif
                                    </td>

                                    <td class="p-4">
                                        <form action="{{ route('admin.transactions.drive', $booking->id) }}"
                                            method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="url" name="google_drive_link"
                                                value="{{ $booking->google_drive_link }}"
                                                placeholder="https://drive..."
                                                class="text-xs border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 w-32 px-2 py-1.5"
                                                required>

                                            <button type="submit"
                                                class="bg-gray-800 text-white p-1.5 rounded-lg hover:bg-gray-700 transition"
                                                title="Simpan Link">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                                    </path>
                                                </svg>
                                            </button>

                                            @if ($booking->google_drive_link)
                                                <a href="{{ $booking->google_drive_link }}" target="_blank"
                                                    class="bg-blue-100 text-blue-600 p-1.5 rounded-lg hover:bg-blue-200 transition"
                                                    title="Buka Folder Drive">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </form>
                                    </td>

                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-2">
                                            @if ($booking->status == 'unpaid')
                                                <button
                                                    @click="openModal('approve', '{{ route('admin.transactions.approve', $booking->id) }}')"
                                                    type="button"
                                                    class="bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-600 transition shadow-sm"
                                                    title="Approve DP Manual">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            @endif

                                            @if ($booking->status == 'paid' && $booking->remaining_balance > 0)
                                                <button
                                                    @click="openModal('complete', '{{ route('admin.transactions.complete', $booking->id) }}')"
                                                    type="button"
                                                    class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition shadow-sm"
                                                    title="Konfirmasi Pelunasan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            @endif

                                            <button
                                                @click="openModal('delete', '{{ route('admin.transactions.destroy', $booking->id) }}')"
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
                                    <td colspan="6" class="text-center py-10">
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
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none sm:text-sm transition"
                                x-text="confirmText">
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
