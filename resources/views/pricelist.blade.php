<x-frontend-layout>
    <x-slot name="title">Pricelist - Alineas Studio</x-slot>

    <div class="relative bg-red-700 pt-10 pb-16 md:pb-20">
        <div class="absolute bottom-0 left-0 w-full flex flex-col">
            <div class="h-3 md:h-3 w-full"
                style="background-image: linear-gradient(90deg, transparent 50%, #ffffff 50%); background-size: 24px 100%; md:background-size: 32px 100%;">
            </div>
            <div class="h-3 md:h-3 w-full"
                style="background-image: linear-gradient(90deg, #ffffff 50%, transparent 50%); background-size: 24px 100%; md:background-size: 32px 100%;">
            </div>
        </div>

        <div class="relative z-10 text-center max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-2">
            <h1 class="text-3xl md:text-5xl font-black tracking-tighter uppercase text-white drop-shadow-sm">
                our Packages
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-4 lg:mx-8 px-4 sm:px-6 lg:px-8 pb-16 pt-12">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
            @foreach ($packages as $package)
                <a href="{{ route('package.show', $package->slug) }}" class="group block">

                    <div
                        class="bg-gray-900 h-60 md:h-80 rounded-2xl p-4 flex flex-col justify-between hover:bg-gray-800 transition relative overflow-hidden border hover:border-red-700 hover:border-2">

                        @if ($package->thumbnail)
                            <img src="{{ asset('storage/' . $package->thumbnail) }}"
                                class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-60 transition duration-500">
                        @endif

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-90">
                        </div>

                        <h3 class="font-bold text-white text-sm md:text-lg z-10 relative leading-tight">
                            {{ $package->name }}
                        </h3>

                        <div class="z-10 relative flex justify-between items-end">
                            <div class="tracking-tight">
                                <p class="text-[10px] text-gray-400 uppercase font-medium">Start from</p>
                                <p class="text-sm md:text-lg font-bold text-white">
                                    {{ number_format($package->price / 1000, 0) }}K
                                </p>
                            </div>

                            <div
                                class="bg-white/10 backdrop-blur-sm p-2 rounded-full group-hover:bg-red-600 transition duration-300">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach

            @if ($packages->isEmpty())
                <div class="col-span-2 md:col-span-4 text-center text-gray-400 py-10">
                    Belum ada paket tersedia.
                </div>
            @endif
        </div>
    </div>

</x-frontend-layout>
