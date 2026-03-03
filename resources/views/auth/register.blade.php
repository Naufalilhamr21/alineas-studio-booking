<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Alineas Studio</title>
    <link rel="icon" type="image/png" href="{{ asset('images/alineas-icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-white">

    <div class="min-h-screen flex flex-col justify-center items-center px-6 py-12 lg:px-8">

        <div class="w-full max-w-sm md:max-w-2xl shadow-lg p-8 rounded-3xl border border-gray-200">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 uppercase tracking-tight">
                    REGISTER
                </h1>
                <p class="mt-3 text-gray-600 text-sm leading-relaxed">
                    Daftar untuk melakukan booking <br> di Alineas Studio.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate x-data="{ showPass: false, showConfirm: false }">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-bold text-gray-900 mb-2">
                        Nama Lengkap
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="block w-full rounded-xl border-gray-400 px-4 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm {{ $errors->has('name') ? 'border-red-500 ring-red-500' : '' }}"
                        style="border-radius: 10px;" placeholder="Contoh: Bahlil Babi">

                    @error('name')
                        <p class="mt-2 text-xs font-bold text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-900 mb-2">
                        Email Address
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="block w-full rounded-xl border-gray-400 px-4 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm {{ $errors->has('email') ? 'border-red-500 ring-red-500' : '' }}"
                        style="border-radius: 10px;" placeholder="nama@email.com">

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

                <div>
                    <label for="phone" class="block text-sm font-bold text-gray-900 mb-2">
                        No. WhatsApp
                    </label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="11" maxlength="15"
                        class="block w-full rounded-xl border-gray-400 px-4 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm {{ $errors->has('phone') ? 'border-red-500 ring-red-500' : '' }}"
                        style="border-radius: 10px;" placeholder="Contoh: 081234567890">

                    @error('phone')
                        <p class="mt-2 text-xs font-bold text-red-600 flex items-center gap-1">
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
                            autocomplete="new-password"
                            class="block w-full rounded-xl border-gray-400 px-4 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm {{ $errors->has('password') ? 'border-red-500 ring-red-500' : '' }}"
                            style="border-radius: 10px;" placeholder="Minimal 8 karakter">

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
                        <p class="mt-2 text-xs font-bold text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div x-data="{ showConfirm: false }">
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-900 mb-2">
                        Konfirmasi Password
                    </label>

                    <div class="relative">
                        <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                            name="password_confirmation" required autocomplete="new-password"
                            class="block w-full rounded-xl border-gray-400 px-4 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm {{ $errors->has('password_confirmation') ? 'border-red-500 ring-red-500' : '' }}"
                            style="border-radius: 10px;" placeholder="Ulangi password">

                        <button type="button" @click="showConfirm = !showConfirm"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-red-700 focus:outline-none transition">

                            <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <svg x-show="showConfirm" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.058 10.058 0 01-3.7 5.59m-1.4-1.4l2.29 2.29" />
                            </svg>
                        </button>
                    </div>

                    @error('password_confirmation')
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
                        class="flex w-full justify-center rounded-xl bg-red-700 px-3 py-3.5 text-sm font-bold leading-6 text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 uppercase tracking-wide transition transform active:scale-[0.98]"
                        style="border-radius: 12px;">
                        DAFTAR
                    </button>
                </div>
            </form>

            <p class="mt-8 text-center text-sm text-gray-600">
                Sudah mempunyai akun?
                <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:text-red-700">
                    Login
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
