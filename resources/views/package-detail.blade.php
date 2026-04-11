<x-frontend-layout>
    <x-slot name="title">{{ $package->name }} - Alineas Studio</x-slot>

    <div class="bg-white min-h-screen pt-6 lg:pt-8 pb-16 relative">
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
                            <img src="{{ Storage::disk('s3')->url($package->thumbnail) }}" alt="{{ $package->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif

                        <a href="{{ route('gallery', ['package' => $package->id]) }}"
                            class="inline-flex absolute bottom-4 left-4 bg-gray-100 hover:bg-gray-200 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-gray-900">
                            {{ $package->name }} Gallery
                        </a>
                    </div>
                </div>

                <div class="space-y-6">
                    <h1>
                        <span class="text-red-700 text-2xl lg:text-3xl font-bold leading-tight">Rp{{ number_format($package->price, 0, ',', '.') }}</span>
                    </h1>

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
            <div class="lg:hidden w-full mt-8">
                <a href="{{ route('front.booking', $package->slug) }}"
                    class="block w-full text-center bg-red-700 text-white py-3 rounded-full font-bold text-md hover:bg-red-800 transition active:scale-[0.98]">
                    Booking
                </a>
            </div>
        </div>
    </div>

</x-frontend-layout>
