<aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
    class="fixed inset-y-0 left-0 z-30 w-72 transition duration-300 transform bg-gray-100 lg:translate-x-0 lg:static lg:inset-auto
    
    /* GAYA DESKTOP (Floating Style) */
    lg:m-4 lg:rounded-2xl flex flex-col justify-between">

    <div class="px-6 py-6 overflow-y-auto">

        <div class="flex items-center justify-between mb-8 mx-3">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img src="{{ asset('images/alineas-logo.png') }}" alt="Logo Alineas" class="h-10 w-auto object-contain">
            </a>

            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-red-600 lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <div class="mb-2">
            <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Menu</p>
            <nav class="space-y-1">

                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 text-sm font-semibold transition-all duration-200 rounded-xl group
                   {{ request()->routeIs('dashboard')
                       ? 'bg-red-600 text-white'
                       : 'text-gray-500 hover:bg-red-100 hover:text-red-600' }}">

                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-red-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="mx-3">Dashboard</span>
                </a>

                <a href="{{ route('packages.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-semibold transition-all duration-200 rounded-xl group
                   {{ request()->routeIs('packages.*')
                       ? 'bg-red-600 text-white'
                       : 'text-gray-500 hover:bg-red-100 hover:text-red-600' }}">

                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('packages.*') ? 'text-white' : 'text-gray-400 group-hover:text-red-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <span class="mx-3">Packages</span>
                </a>

                <a href="#"
                    class="flex items-center px-4 py-3 text-sm font-semibold text-gray-500 transition-all duration-200 rounded-2xl hover:bg-red-50 hover:text-red-600 group">
                    <svg class="w-5 h-5 text-gray-400 transition-colors group-hover:text-red-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="mx-3">Gallery</span>
                </a>
            </nav>
        </div>

        <div class="mt-8">
            <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">General</p>
            <nav class="space-y-1">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center px-4 py-3 text-sm font-semibold text-gray-500 transition-all duration-200 rounded-2xl hover:bg-red-50 hover:text-red-600 group">
                        <svg class="w-5 h-5 text-gray-400 transition-colors group-hover:text-red-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span class="mx-3">Logout</span>
                    </a>
                </form>
            </nav>
        </div>
    </div>

    <div class="p-4">
        <a href="">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-100 hover:bg-gray-50">
                <div class="flex-shrink-0">
                    <div
                        class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-red-100 font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </a>
    </div>

</aside>
