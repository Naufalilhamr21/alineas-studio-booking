<x-frontend-layout>
    <x-slot name="title">Gallery - Alineas Studio</x-slot>

    <div class="relative bg-red-700 pt-10 pb-16 md:pb-20 mb-8">
        <div class="absolute bottom-0 left-0 w-full flex flex-col">
            <div class="h-3 md:h-3 w-full"
                style="background-image: linear-gradient(90deg, transparent 50%, #ffffff 50%); background-size: 24px 100%; md:background-size: 32px 100%;">
            </div>
            <div class="h-3 md:h-3 w-full"
                style="background-image: linear-gradient(90deg, #ffffff 50%, transparent 50%); background-size: 24px 100%; md:background-size: 32px 100%;">
            </div>
        </div>

        <div class="relative z-10 text-center max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-2">
            <h1 class="text-3xl md:text-5xl font-black tracking-tighter uppercase text-white">
                our Gallery
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-4 lg:mx-8 px-4 sm:px-6 lg:px-8 pb-0 md:pb-16" x-data="{
        activeTab: '{{ request('package', 'all') }}',
        lightboxOpen: false,
        imgUrl: '',
        imgCat: '',
        openLightbox(url, category) {
            this.imgUrl = url;
            this.imgCat = category;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden'; // Matikan scroll body
        },
        closeLightbox() {
            this.lightboxOpen = false;
            this.imgUrl = '';
            document.body.style.overflow = 'auto'; // Hidupkan scroll body
        }
    }">

        <div class="flex justify-start md:justify-center mb-8 overflow-x-auto pb-4 hide-scrollbar px-4">
            <div class="inline-flex space-x-2 bg-gray-100 p-1.5 rounded-full whitespace-nowrap">
                <button @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-900'"
                    class="px-6 py-2 rounded-full text-sm font-bold transition duration-300">
                    All Photos
                </button>

                @foreach ($packages as $package)
                    <button @click="activeTab = '{{ $package->id }}'"
                        :class="activeTab === '{{ $package->id }}' ? 'bg-white text-red-600 shadow-sm' :
                            'text-gray-500 hover:text-gray-900'"
                        class="px-6 py-2 rounded-full text-sm font-bold transition duration-300 whitespace-nowrap">
                        {{ $package->name }}
                    </button>
                @endforeach
            </div>
        </div>

        @if ($galleries->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                <p class="text-gray-400">Belum ada foto yang diupload.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach ($galleries as $gallery)
                    <div x-show="activeTab === 'all' || activeTab === '{{ $gallery->package_id }}'"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="relative group break-inside-avoid cursor-pointer" {{-- PERBAIKAN 1: Path gambar untuk modal Lightbox --}}
                        @click="openLightbox('{{ Storage::disk('s3')->url($gallery->image_path) }}')">

                        <div class="aspect-[4/5] w-full overflow-hidden rounded-2xl bg-gray-200 relative">
                            {{-- PERBAIKAN 2: Path gambar untuk tampilan grid thumbnail --}}
                            <img src="{{ Storage::disk('s3')->url($gallery->image_path) }}" alt="Gallery Image"
                                loading="lazy"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">

                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-center items-center">
                                <svg class="w-8 h-8 text-white mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                    </path>
                                </svg>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

        <template x-teleport="body">
            <div x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[999] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
                @keydown.escape.window="closeLightbox()">

                <button @click="closeLightbox()"
                    class="absolute top-6 right-6 text-white hover:text-red-500 z-50 transition">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <div class="relative max-w-5xl w-full max-h-screen flex flex-col items-center"
                    @click.outside="closeLightbox()">
                    <img :src="imgUrl" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl object-contain">
                </div>

            </div>
        </template>

    </div>

</x-frontend-layout>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
