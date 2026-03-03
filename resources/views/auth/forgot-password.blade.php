<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Alineas Studio</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <div class="min-h-screen flex flex-col justify-center items-center px-6 py-12 lg:px-8">

        <div class="w-full max-w-sm md:max-w-md bg-white shadow-xl p-8 md:p-10 rounded-3xl border border-gray-100">

            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tighter">
                    LUPA PASSWORD?
                </h1>
                <p class="mt-3 text-gray-500 text-sm leading-relaxed">
                    Masukkan alamat email Anda yang terdaftar.
                </p>
            </div>

            @if (session('status'))
                <div
                    class="mb-6 p-4 text-sm text-green-700 bg-green-50 rounded-xl border border-green-200 font-bold flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6" novalidate>
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-900 mb-2">
                        Email Address
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="block w-full rounded-xl border-gray-300 px-4 py-3.5 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm {{ $errors->has('email') ? 'border-red-500 ring-red-500 bg-red-50' : '' }}"
                        placeholder="nama@email.com">

                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="flex w-full justify-center rounded-xl bg-red-700 px-3 py-3.5 text-sm font-bold leading-6 text-white shadow-md hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 uppercase tracking-wide transition">
                        enter
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-gray-100 pt-6">
                <a href="{{ route('login') }}"
                    class="text-xs text-gray-500 hover:text-red-700 transition flex items-center justify-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke halaman Login
                </a>
            </div>

        </div>
    </div>
</body>

</html>
