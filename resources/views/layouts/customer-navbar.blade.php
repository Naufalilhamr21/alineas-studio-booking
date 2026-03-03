<nav x-data="{ sidebarOpen: false, dropdownOpen: false }" class="bg-white py-4 px-4 border-b border-gray-200 sticky top-0 z-50 font-sans">
    <div class="max-w-7xl mx-1 lg:mx-14 flex justify-between items-center relative">

        <div class="flex items-center md:hidden">
            <button @click="sidebarOpen = true" class="text-gray-800 focus:outline-none hover:text-red-600 transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>

        <a href="{{ route('home') }}"
            class="absolute left-1/2 transform -translate-x-1/2 md:static md:transform-none md:flex items-center">
            @if (file_exists(public_path('images/alineas-logo.png')))
                <img src="{{ asset('images/alineas-logo.png') }}" alt="ALINEAS"
                    class="h-10 md:h-10 w-auto object-contain">
            @else
                <span class="text-2xl font-black tracking-tighter text-red-600">ALINEAS<span
                        class="text-gray-800">STUDIO</span></span>
            @endif
        </a>

        <div class="hidden space-x-8 sm:-my-px sm:ml-10 md:flex items-center">

            {{-- MENU 1: HOME (Semua User) --}}
            <a href="{{ route('home') }}"
                class="text-md {{ request()->routeIs('home') ? 'text-red-600 font-semibold' : 'text-gray-600 hover:text-red-600' }} transition">
                Home
            </a>

            {{-- MENU 2: PRICELIST (Semua User) --}}
            {{-- Menggunakan route('home') . '#id' agar bisa diklik dari halaman dashboard --}}
            <a href="{{ route('pricelist') }}"
                class="text-md {{ request()->routeIs('pricelist') ? 'text-red-600 font-semibold' : 'text-gray-600 hover:text-red-600' }} transition">
                Pricelist
            </a>

            {{-- MENU 3: GALLERY (Semua User) --}}
            <a href="{{ route('gallery') }}"
                class="text-md {{ request()->routeIs('gallery') ? 'text-red-600 font-semibold' : 'text-gray-600 hover:text-red-600' }} transition">
                Gallery
            </a>
        </div>

        <div class="flex items-center">
            @if (Route::has('login'))
                @auth
                    <div class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false"
                            class="inline-flex items-center px-1 py-1 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 gap-2">

                            <div
                                class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold border border-red-200">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>

                        <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-2 origin-top-right"
                            style="display: none;">

                            <a href="{{ route('profile.edit') }}"
                                class="transition-all duration-200 text-gray-600 hover:bg-red-50 hover:text-red-600 group">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <span class="block text-sm font-bold">{{ Auth::user()->name }}</span>
                                    <span class="block text-xs">{{ Auth::user()->email }}</span>
                                </div>
                            </a>

                            <a href="{{ route('booking.history') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-600 transition-all duration-200 hover:bg-red-50 hover:text-red-600 group">
                                <svg class="w-5 h-5 text-gray-600 transition-colors group-hover:text-red-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <span class="mx-3">Riwayat Transaksi</span>
                            </a>

                            <div x-data="{ openLogoutModal: false }">

                                <a href="#" @click.prevent="openLogoutModal = true"
                                    class="flex items-center px-4 py-3 text-sm text-gray-600 transition-all duration-200 hover:bg-red-50 hover:text-red-600 group">
                                    <svg class="w-5 h-5 text-gray-600 transition-colors group-hover:text-red-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span class="mx-3">Logout</span>
                                </a>

                                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                                    @csrf
                                </form>

                                <template x-teleport="body">
                                    <div x-show="openLogoutModal"
                                        class="fixed inset-0 z-[99] flex items-center justify-center min-h-screen px-4 py-6 sm:px-0"
                                        style="display: none;">

                                        <div x-show="openLogoutModal" x-transition:enter="ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-70 backdrop-blur-sm"
                                            @click="openLogoutModal = false"></div>

                                        <div x-show="openLogoutModal" x-transition:enter="ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                            x-transition:leave="ease-in duration-200"
                                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            class="relative w-full max-w-sm overflow-hidden transition-all transform bg-white rounded-2xl shadow-xl">

                                            <div class="p-6 text-center">
                                                <div
                                                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>

                                                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Logout</h3>
                                                <p class="mt-2 text-sm text-gray-500">
                                                    Apakah Anda yakin ingin keluar dari akun ini?
                                                </p>
                                            </div>

                                            <div
                                                class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                                                <button @click="document.getElementById('logout-form').submit()"
                                                    type="button"
                                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:text-sm transition">
                                                    Ya, Keluar
                                                </button>

                                                <button @click="openLogoutModal = false" type="button"
                                                    class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm transition">
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-gray-800 text-white px-5 py-2.5 rounded-full text-xs font-bold hover:bg-gray-900">
                        Login
                    </a>
                @endauth
            @endif
        </div>
    </div>

    <div x-show="sidebarOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
        @click="sidebarOpen = false" style="display: none;"></div>

    <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl overflow-y-auto md:hidden" style="display: none;">

        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <a href="{{ route('home') }}">
                @if (file_exists(public_path('images/alineas-logo.png')))
                    <img src="{{ asset('images/alineas-logo.png') }}" alt="ALINEAS"
                        class="h-10 md:h-10 w-auto object-contain">
                @else
                    <span class="text-2xl font-black tracking-tighter text-red-600">ALINEAS<span
                            class="text-gray-800">STUDIO</span></span>
                @endif
            </a>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-red-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <nav class="px-4 py-6 space-y-2">
            <a href="{{ route('home') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl {{ request()->routeIs('home') ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Home
            </a>

            <a href="{{ route('pricelist') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl {{ request()->routeIs('pricelist') ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Pricelist
            </a>

            <a href="{{ route('gallery') }}"
                class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl {{ request()->routeIs('gallery') ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Gallery
            </a>
        </nav>
    </div>
</nav>
