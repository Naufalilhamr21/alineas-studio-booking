<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - Alineas Studio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 font-sans antialiased">
    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md w-full text-center border border-gray-100">

        <div class="flex justify-center mb-6">
            <div class="bg-red-50 p-4 rounded-full">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-3">Cek Email Anda</h2>

        <p class="text-gray-500 mb-6 text-sm leading-relaxed">
            <strong>Terima kasih telah mendaftar!</strong> Silakan cek email Anda dan klik tautan verifikasi untuk
            mengaktifkan akun.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl">
                Tautan verifikasi yang baru telah berhasil dikirim ke email Anda!
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-800 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 shadow-md hover:shadow-lg focus:ring-4 focus:ring-red-200">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-white hover:bg-gray-50 text-gray-600 font-medium py-3 px-4 rounded-xl transition duration-200 border border-gray-200">
                    Masuk dengan akun lain
                </button>
            </form>
        </div>

    </div>
</body>

</html>
