<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Alineas Studio</title>
    <link rel="icon" type="image/png" href="{{ asset('images/alineas-icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-white">

    <div class="min-h-screen flex flex-col justify-center items-center px-6 py-12 lg:px-8">

        <div class="w-full max-w-sm md:max-w-2xl shadow-lg p-8 rounded-3xl border border-gray-200">

            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-gray-900 uppercase tracking-tight">
                    LOGIN
                </h1>
                <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                    Masuk untuk melakukan booking <br> di Alineas Studio.
                </p>
            </div>

            @if (session('success'))
                <div
                    class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-xl shadow-sm text-sm font-medium flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-900 mb-2">
                        Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="block w-full rounded-xl border-gray-400 px-4 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm {{ $errors->has('email') ? 'border-red-500 ring-red-500' : '' }}"
                        style="border-radius: 10px;">

                    @error('email')
                        <p class="mt-2 text-xs text-red-600 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-sm font-bold text-gray-900 mb-2">
                        Password
                    </label>

                    <div class="relative">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                            autocomplete="current-password"
                            class="block w-full rounded-xl border-gray-400 px-4 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm {{ $errors->has('password') ? 'border-red-500 ring-red-500' : '' }}"
                            style="border-radius: 10px;">

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-red-700 focus:outline-none transition">

                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.058 10.058 0 01-3.7 5.59m-1.4-1.4l2.29 2.29" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-2 text-xs font-medium text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm font-bold text-red-700 hover:text-red-800">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-xl bg-red-700 px-3 py-3.5 text-sm font-bold leading-6 text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 uppercase tracking-wide"
                        style="border-radius: 12px;">
                        LOGIN
                    </button>
                </div>
            </form>

            <p class="mt-8 text-center text-sm text-gray-600">
                Belum mempunyai akun?
                <a href="{{ route('register') }}" class="font-bold text-gray-900 hover:text-red-700">
                    Daftar
                </a>
            </p>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}"
                    class="text-xs text-gray-500 hover:text-red-700 transition flex items-center justify-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
</body>

</html>
