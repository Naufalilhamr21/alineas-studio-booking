<aside
    :class="[
        sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in',
        sidebarCollapsed ? 'lg:w-20' : 'lg:w-72'
    ]"
    class="fixed inset-y-0 left-0 z-30 transition-all duration-300 transform bg-white border-r border-gray-200 lg:translate-x-0 lg:static lg:inset-auto flex flex-col justify-between shadow-sm">

    <div class="overflow-y-auto h-full flex flex-col hide-scrollbar">
        <div class="flex items-center h-20 border-b border-gray-100 transition-all duration-300 relative"
            :class="sidebarCollapsed ? 'justify-center px-6' : 'justify-between px-5'">

            <a x-show="!sidebarCollapsed" href="{{ route('dashboard') }}" class="flex items-center">
                <img src="{{ asset('images/alineas-logo.png') }}" alt="Logo Alineas"
                    class="object-contain transition-all duration-300 h-10">
            </a>

            <button @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden lg:flex items-center justify-center text-gray-400 hover:text-red-700 transition-colors"
                :class="sidebarCollapsed ? 'w-8 h-8 rounded-full shadow-sm' :
                    'w-8 h-8 rounded-lg'">
                <svg class="w-4 h-4 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>

            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <div class="flex-1 py-6 space-y-8" :class="sidebarCollapsed ? 'px-2' : 'px-4'">
            <div>
                <p x-show="!sidebarCollapsed"
                    class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Menu Utama</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" title="Dashboard"
                        class="relative group flex items-center text-sm font-semibold transition-all duration-200"
                        :class="sidebarCollapsed
                            ?
                            'justify-center w-12 h-12 mx-auto rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-red-600 text-white shadow-md' : 'text-gray-500 hover:bg-red-100 hover:text-red-600' }}' :
                            'px-3 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}'">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-current' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.packages.index') }}" title="Pricelist"
                        class="relative group flex items-center text-sm font-semibold transition-all duration-200"
                        :class="sidebarCollapsed
                            ?
                            'justify-center w-12 h-12 mx-auto rounded-2xl {{ request()->routeIs('admin.packages.*') ? 'bg-red-600 text-white shadow-md' : 'text-gray-500 hover:bg-red-100 hover:text-red-600' }}' :
                            'px-3 py-3 rounded-xl {{ request()->routeIs('admin.packages.*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}'">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('admin.packages.*') ? 'text-white' : 'text-gray-400 group-hover:text-current' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap">Paket & Harga</span>
                    </a>

                    <a href="{{ route('admin.galleries.index') }}" title="Galeri"
                        class="relative group flex items-center text-sm font-semibold transition-all duration-200"
                        :class="sidebarCollapsed
                            ?
                            'justify-center w-12 h-12 mx-auto rounded-2xl {{ request()->routeIs('admin.galleries.*') ? 'bg-red-600 text-white shadow-md' : 'text-gray-500 hover:bg-red-100 hover:text-red-600' }}' :
                            'px-3 py-3 rounded-xl {{ request()->routeIs('admin.galleries.*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}'">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('admin.galleries.*') ? 'text-white' : 'text-gray-400 group-hover:text-current' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap">Galeri Foto</span>
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" title="Transaksi"
                        class="relative group flex items-center text-sm font-semibold transition-all duration-200"
                        :class="sidebarCollapsed
                            ?
                            'justify-center w-12 h-12 mx-auto rounded-2xl {{ request()->routeIs('admin.transactions.*') ? 'bg-red-600 text-white shadow-md' : 'text-gray-500 hover:bg-red-100 hover:text-red-600' }}' :
                            'px-3 py-3 rounded-xl {{ request()->routeIs('admin.transactions.*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}'">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('admin.transactions.*') ? 'text-white' : 'text-gray-400 group-hover:text-current' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap">Transaksi</span>
                    </a>
                </nav>
            </div>
        </div>

        <div class="border-t border-gray-100 p-4" x-data="{ dropdownOpen: false, openLogoutModal: false }">

            <div x-show="!sidebarCollapsed" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false"
                    class="w-full flex items-center gap-3 p-2 rounded-xl bg-white border border-transparent hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 text-left">

                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-tr from-red-600 to-red-400 flex items-center justify-center text-white font-bold shadow-sm">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'user@email.com' }}</p>
                    </div>
                </button>

                <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute bottom-full left-0 mb-2 w-full rounded-xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] bg-white border border-gray-100 py-2 z-50"
                    style="display: none;">

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Edit Profil
                    </a>

                    <button @click="openLogoutModal = true; dropdownOpen = false"
                        class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors text-left">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Logout
                    </button>
                </div>
            </div>

            <div x-show="sidebarCollapsed" class="flex flex-col gap-2">
                <a href="{{ route('profile.edit') }}" title="Edit Profil"
                    class="justify-center flex items-center w-12 h-12 mx-auto rounded-2xl text-gray-400 hover:bg-red-100 hover:text-red-600 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </a>

                <button @click="openLogoutModal = true" title="Logout"
                    class="justify-center flex items-center w-12 h-12 mx-auto rounded-2xl text-gray-400 hover:bg-red-100 hover:text-red-600 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900">Konfirmasi Logout</h3>
                            <p class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin keluar dari akun ini?</p>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                            <button @click="document.getElementById('logout-form').submit()" type="button"
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
</aside>
