<x-frontend-layout>
    <x-slot name="title">{{ $package->name }} - Alineas Studio</x-slot>

    <div class="bg-white min-h-screen pt-6 lg:pt-8 pb-24 relative">
        <div class="max-w-7xl mx-4 lg:mx-8 px-4 sm:px-6 lg:px-8 mt-2">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">{{ $package->name }}</h1>
                    <p class="text-gray-500 text-sm lg:text-base mt-1">
                        {{ $package->tagline ?? 'Paket Foto Special Untukmu' }}
                    </p>
                </div>

                <a href="{{ route('front.booking', $package->slug) }}"
                    class="hidden lg:flex text-center bg-red-700 text-white items-center my-1 px-6 py-2 text-sm lg:text-base rounded-full font-bold hover:bg-red-800 transition">
                    Booking
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">

                <div class="w-full">
                    <div class="aspect-[4/3] w-full bg-gray-200 rounded-3xl overflow-hidden shadow-sm relative group">
                        @if ($package->thumbnail)
                            <img src="{{ asset('storage/' . $package->thumbnail) }}" alt="{{ $package->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif

                        <a href="{{ route('gallery', ['package' => $package->id]) }}"
                            class="hidden lg:inline-flex absolute bottom-4 left-4 bg-gray-100 hover:bg-gray-200 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-gray-900">
                            {{ $package->name }} Gallery
                        </a>
                    </div>
                </div>

                <div class="space-y-6">

                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-3">Benefit</h3>
                        <ul class="list-disc pl-5 text-sm text-gray-600 leading-relaxed space-y-2">
                            @foreach (explode("\n", $package->benefit) as $item)
                                @if (trim($item))
                                    <li>{{ ltrim($item, '- ') }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-3">Syarat dan Ketentuan</h3>
                        <ul class="list-disc pl-5 text-sm text-gray-600 leading-relaxed space-y-2">
                            <li>Booking sesi yang sudah masuk sistem tidak bisa refund/cancel.</li>
                            <li>Reschedule dapat dilakukan selambat-lambatnya 3 jam sebelum sesi dan mendapat konfirmasi
                                Admin.</li>
                            <li>Reschedule hanya dapat dilakukan satu kali.</li>
                            <li>Toleransi keterlambatan maksimal 15 menit.</li>
                            <li>Disarankan datang 15 menit lebih awal untuk persiapan.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="lg:hidden w-full pt-10">
                <a href="{{ route('front.booking', $package->slug) }}"
                    class="block w-full text-center bg-red-700 text-white py-3 rounded-full font-bold text-md hover:bg-red-800 transition active:scale-[0.98]">
                    Booking
                </a>
            </div>
        </div>
    </div>

</x-frontend-layout>

{{-- <x-frontend-layout>
    <div class="bg-white min-h-screen pb-32 lg:pb-24 relative">
        <div class="max-w-7xl mx-4 lg:mx-8 px-4 sm:px-6 lg:px-8 mt-2">

            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">{{ $package->name }}</h1>
                    <p class="text-gray-500 text-sm lg:text-base mt-1">
                        Untuk 3 s/d 30 Orang
                    </p>
                </div>

                <a href="{{ route('front.booking', $package->slug) }}"
                    class="hidden lg:flex text-center bg-red-700 text-white items-center my-2 px-8 py-3 text-base rounded-full font-bold hover:bg-red-800 transition shadow-lg">
                    BOOKING SEKARANG
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">

                <div class="w-full">
                    <div class="aspect-[4/3] w-full bg-gray-200 rounded-3xl overflow-hidden shadow-sm relative group">
                        @if ($package->thumbnail)
                            <img src="{{ asset('storage/' . $package->thumbnail) }}" alt="{{ $package->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif

                        <a href="#"
                            class="absolute bottom-4 left-4 bg-white/90 hover:bg-white backdrop-blur px-4 py-2 rounded-full text-xs font-bold text-gray-900 shadow-sm transition">
                            📸 {{ $package->name }} Gallery
                        </a>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-3">Benefit</h3>
                        <div class="text-sm text-gray-600 leading-relaxed space-y-2">
                            <p class="whitespace-pre-line">{{ $package->description }}</p>

                            <ul class="list-disc pl-5 space-y-1 mt-2">
                                <li>{{ $package->duration_minutes }} menit sesi foto</li>
                                <li>Semua soft file via Google Drive</li>
                                <li>Private Studio Session</li>
                                <li>Free WiFi & Dressing Room</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-3">Syarat dan Ketentuan</h3>
                        <ul class="list-disc pl-5 text-sm text-gray-600 leading-relaxed space-y-2">
                            <li>Booking tidak bisa refund/cancel.</li>
                            <li>Reschedule maksimal 3 jam sebelum sesi.</li>
                            <li>Toleransi keterlambatan maksimal 15 menit.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="lg:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-100 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] z-50">
            <a href="{{ route('front.booking', $package->slug) }}"
                class="block w-full text-center bg-red-700 text-white py-4 rounded-2xl font-bold text-lg hover:bg-red-800 transition active:scale-[0.98]">
                BOOKING SEKARANG
            </a>
        </div>
    </div>
</x-frontend-layout> --}}
