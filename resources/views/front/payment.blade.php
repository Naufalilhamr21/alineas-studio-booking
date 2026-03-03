<x-app-layout>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-3 lg:mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-10">
                <h1 class="text-xl lg:text-2xl font-extrabold text-gray-900">Konfirmasi Pembayaran</h1>
                <p class="text-gray-500 mt-2">Selesaikan pembayaran untuk mengamankan slot foto Anda.</p>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-red-600 px-8 py-6 text-white flex justify-between items-center">
                    <div>
                        <p class="opacity-80 text-sm">Kode Booking</p>
                        <p class="font-bold text-xxl tracking-wide">{{ $booking->booking_code }}</p>
                    </div>
                    <div class="text-right">
                        <p class="opacity-80 text-sm">Total Tagihan</p>
                        <p class="font-bold text-xl">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="p-8">
                    <div class="space-y-6">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                            <span class="text-gray-600">Paket Foto</span>
                            <span class="font-bold text-gray-900">{{ $booking->package->name }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                            <span class="text-gray-600">Jadwal Sesi</span>
                            <div class="text-right">
                                <span class="block font-bold text-gray-900">
                                    {{ $booking->start_time->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                </span>
                                <span class="block text-sm text-gray-500">
                                    Jam
                                    {{ $booking->start_time_wib }}
                                    -
                                    {{ $booking->end_time_wib }}
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status Pembayaran</span>
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold">
                                {{ strtoupper($booking->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-10">
                        <button id="pay-button"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-md py-4 rounded-xl">
                            Bayar Sekarang
                        </button>

                        <p class="text-center text-xs text-gray-400 mt-4">
                            Pembayaran diamankan oleh Midtrans Payment Gateway.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        // Ambil tombol bayar
        var payButton = document.getElementById('pay-button');

        payButton.addEventListener('click', function() {
            // Trigger Snap Popup dengan Token dari Database
            window.snap.pay('{{ $booking->snap_token }}', {

                // Jika sukses bayar
                onSuccess: function(result) {
                    // Redirect ke Dashboard (atau halaman sukses)
                    // Nanti kita buat callback handler untuk update database
                    window.location.href = "{{ route('dashboard') }}";
                },

                // Jika pending (user tutup popup tapi belum bayar di ATM)
                onPending: function(result) {
                    alert("Menunggu pembayaran! Silakan selesaikan pembayaran Anda.");
                    location.reload();
                },

                // Jika error
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    location.reload();
                },

                // Jika ditutup
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        });
    </script>
</x-app-layout>
