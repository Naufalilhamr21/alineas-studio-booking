<x-frontend-layout>
    <section class="max-w-7xl mx-auto">
        <div class="w-full h-[150px] md:h-[440px] relative overflow-hidden group">
            <img src="{{ asset('images/web-banner.png') }}" alt="Alineas Studio Banner"
                class="w-full h-full object-cover object-center">
        </div>
    </section>

    <div class="text-center my-10 lg:my-16">
        <h1 class="text-2xl md:text-4xl font-black text-gray-800 tracking-[-0.05em]">
            POSE WITH <span class="text-red-600">ALINEAS</span>
        </h1>
    </div>

    <section id="packages" class="max-w-7xl mx-4 lg:mx-14 px-4 mb-16 lg:mb-24">
        <div class="flex justify-between items-end mb-6">
            <h2 class="text-lg md:text-2xl font-bold text-red-600 tracking-[-0.05em] uppercase">Our Package</h2>
            <a href="{{ route('pricelist') }}"
                class="text-xs lg:text-sm font-bold border border-gray-400 px-4 py-2 rounded-full hover:bg-gray-200 transition">See
                more</a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach ($packages as $package)
                <a href="{{ route('package.show', $package->slug) }}" class="group block">

                    <div
                        class="bg-gray-900 h-64 md:h-80 rounded-2xl p-4 flex flex-col justify-between hover:bg-gray-800 transition relative overflow-hidden border hover:border-red-600 hover:border-2">

                        @if ($package->thumbnail)
                            <img src="{{ Storage::disk('s3')->url($package->thumbnail) }}""
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

        @if ($packages->isEmpty())
            <div class="col-span-2 md:col-span-4 text-center text-gray-400 py-10">
                Belum ada paket tersedia.
            </div>
        @endif
    </section>

    <section id="gallery" class="w-full bg-red-700 py-10 mb-12">

        <div class="max-w-7xl mx-auto px-8 lg:px-16">
            <div class="flex justify-between items-end mb-6">
                <h2 class="text-lg md:text-2xl font-bold text-white tracking-[-0.05em] uppercase">Gallery</h2>
                <a href="{{ route('gallery') }}"
                    class="text-xs lg:text-sm font-bold text-red-700 bg-white border border-white px-4 py-2 rounded-full hover:bg-gray-200 transition">
                    See more
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 pb-2">
                @foreach ($packages as $package)
                    @php
                        $galleryImage = $package->galleries->first();
                        $imageSource = $galleryImage
                            ? Storage::disk('s3')->url($galleryImage->image_path)
                            : Storage::disk('s3')->url($package->thumbnail);
                    @endphp

                    <a href="{{ route('gallery', ['package' => $package->id]) }}" class="group block w-full">
                        <div class="w-full h-64 md:h-80 rounded-xl overflow-hidden relative shadow-md">

                            <img src="{{ $imageSource }}"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700 ease-in-out">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                            </div>

                            <div
                                class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition duration-300">
                            </div>

                            <div class="absolute bottom-0 left-0 p-4 w-full">
                                <h3 class="text-white text-base font-bold md:text-xl tracking-tight leading-tight">
                                    {{ $package->name }}
                                </h3>
                            </div>

                        </div>
                    </a>
                @endforeach

                @if ($packages->isEmpty())
                    <div class="col-span-2 lg:col-span-4 text-center text-gray-300 py-10">
                        Belum ada galeri tersedia.
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section id="reviews" class="w-full max-w-7xl pt-8 mx-auto px-4 lg:px-14 mb-20 text-center">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 uppercase tracking-[-0.05em] mb-6">What Our Customers Say
        </h2>

        <div class="w-full max-w-full overflow-hidden px-1 md:px-8 transform scale-[0.90] sm:scale-100 origin-top">
            <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
            <div class="elfsight-app-3f8acc35-fce7-478e-8145-226f9f636c9b" data-elfsight-app-lazy></div>
        </div>
    </section>
</x-frontend-layout>
