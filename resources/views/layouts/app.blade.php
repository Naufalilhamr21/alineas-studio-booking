<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Alineas Studio') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/alineas-icon.png') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white text-gray-800">
    @if (Auth::user()->role === 'admin')
        <div x-data="{
            sidebarOpen: false,
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true'
        }" x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))" class="flex h-screen overflow-hidden bg-gray-50">

            @include('layouts.admin-sidebar')

            <div class="flex flex-col flex-1 w-0 overflow-hidden">
                <header
                    class="relative flex items-center justify-center px-6 py-4 bg-white border-b border-gray-200 lg:hidden">
                    <button @click="sidebarOpen = true"
                        class="absolute left-6 text-gray-500 focus:outline-none hover:text-red-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('images/alineas-logo.png') }}" alt="Logo Alineas"
                            class="h-8 w-auto object-contain">
                    </a>
                </header>

                <main class="flex-1 relative overflow-y-auto focus:outline-none p-4 lg:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    @else
        {{-- LAYOUT KHUSUS CUSTOMER (TOPBAR STYLE) --}}
        <div class="min-h-screen">
            @include('layouts.customer-navbar')
            <main>
                {{ $slot }}
            </main>
        </div>
    @endif

    @if (Auth::check() && Auth::user()->role === 'admin')
        <script type="module">
            setTimeout(() => {
                if (window.Echo) {
                    window.Echo.channel('admin-channel')
                        .listen('NewBookingCreated', (e) => {
                            Swal.fire({
                                title: 'Pesanan Baru!',
                                text: `${e.booking.user ? e.booking.user.name : 'Customer'} baru saja booking paket ${e.booking.package ? e.booking.package.name : 'Foto'}!`,
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 6000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });
                        });
                }
            }, 1000);
        </script>
    @endif
</body>

</html>
