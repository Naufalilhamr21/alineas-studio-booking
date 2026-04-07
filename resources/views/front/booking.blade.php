<x-app-layout>
    @if (session('error'))
        <div
            class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm relative max-w-6xl mx-auto mt-4">
            <strong class="font-bold">Terjadi Kesalahan!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>


    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-6xl lg:mx-auto mx-3 px-4 sm:px-6 lg:px-8">


            <div x-data="bookingSystem({{ $package->id }}, {{ $package->price }}, {{ $package->max_pax ?? 1 }}, {{ $package->extra_price_per_pax ?? 0 }})">


                {{-- ========================================== --}}
                {{-- TAHAP 1: FORM JUMLAH ORANG --}}
                {{-- ========================================== --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="max-w-md mx-auto">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center">
                        <div
                            class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-7">Jumlah Orang</h2>
                        {{-- Counter Orang --}}
                        <div class="flex items-center justify-center gap-6 mb-8">
                            <button @click="decrementPax()"
                                class="w-12 h-12 rounded-full bg-gray-100 hover:bg-red-50 hover:text-red-600 flex items-center justify-center text-gray-600 transition font-bold text-xl focus:outline-none">
                                -
                            </button>
                            <div class="w-16 text-center">
                                <span class="text-4xl font-extrabold text-gray-900" x-text="totalPax"></span>
                            </div>
                            <button @click="incrementPax()"
                                class="w-12 h-12 rounded-full bg-gray-100 hover:bg-red-50 hover:text-red-600 flex items-center justify-center text-gray-600 transition font-bold text-xl focus:outline-none">
                                +
                            </button>
                        </div>


                        {{-- Rincian Biaya --}}
                        <div class="bg-gray-50 rounded-2xl p-5 mb-8 text-left">
                            <div class="flex justify-between items-center mb-2 text-sm">
                                <span class="text-gray-500">Harga Paket Dasar (Max 4 orang)</span>
                                <span class="font-semibold text-gray-800" x-text="formatRupiah(basePrice)"></span>
                            </div>
                            <div x-show="totalPax > maxPax"
                                class="flex justify-between items-center mb-4 text-sm text-red-600">
                                <span>Biaya Tambahan (<span x-text="totalPax - maxPax"></span> orang)</span>
                                <span class="font-semibold"
                                    x-text="'+ ' + formatRupiah((totalPax - maxPax) * extraPrice)"></span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 flex justify-between items-center">
                                <span class="font-bold text-gray-700">Total Harga</span>
                                <span class="font-extrabold text-lg text-gray-900"
                                    x-text="formatRupiah(totalPrice)"></span>
                            </div>
                        </div>


                        <button @click="nextStep()"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl transition shadow-sm shadow-red-200">
                            Lanjut Pilih Jadwal
                        </button>
                    </div>
                </div>


                {{-- ========================================== --}}
                {{-- TAHAP 2: KALENDER (Disembunyikan awalnya) --}}
                {{-- ========================================== --}}
                <div x-cloak x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex flex-col md:flex-row gap-8 items-start">


                    {{-- Container Kiri: Kalender --}}
                    <div class="w-full md:w-[380px] flex-none">


                        {{-- TOMBOL KEMBALI (Dipindah ke atas card kalender) --}}
                        <div class="mb-4">
                            <button @click="prevStep()"
                                class="bg-white border border-gray-200 text-gray-600 hover:text-red-600 hover:border-red-200 shadow-sm rounded-full px-4 py-2 transition flex items-center gap-2 text-sm font-semibold w-fit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Kembali ke Jumlah Orang
                            </button>
                        </div>


                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                            <div class="flex justify-between items-center mb-6 px-2">
                                <button @click="changeMonth(-1)"
                                    class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition"><svg
                                        class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg></button>
                                <h2 class="text-md lg:text-xl font-bold text-gray-800"
                                    x-text="monthNames[month] + ' ' + year"></h2>
                                <button @click="changeMonth(1)"
                                    class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition"><svg
                                        class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg></button>
                            </div>


                            <div class="grid grid-cols-7 mb-2">
                                <template x-for="day in ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']">
                                    <div class="text-center text-xs font-semibold text-gray-400 py-1" x-text="day">
                                    </div>
                                </template>
                            </div>


                            <div class="grid grid-cols-7 gap-1">
                                <template x-for="blank in blankDays">
                                    <div class="h-10"></div>
                                </template>
                                <template x-for="date in no_of_days">
                                    <div class="h-10">
                                        <button @click="selectDate(date)"
                                            :disabled="isPastDate(date) || calendarStatus[date] === 'full'"
                                            :class="{
                                                'bg-red-600 text-white shadow-md': isSelected(date),
                                                'bg-gray-100 text-gray-300 cursor-not-allowed decoration-slice line-through': calendarStatus[
                                                    date] === 'full',
                                                'text-gray-700 hover:bg-red-50 hover:text-red-600': !isSelected(date) &&
                                                    !isToday(date) && !isPastDate(date) && calendarStatus[
                                                        date] !== 'full',
                                                'text-red-600 font-bold border border-red-500': isToday(date) && !
                                                    isSelected(date),
                                                'text-gray-300 cursor-not-allowed': isPastDate(date)
                                            }"
                                            class="w-full h-full rounded-xl text-sm font-medium transition-all duration-200 flex flex-col items-center justify-center relative group">
                                            <span x-text="date"></span>
                                            <span x-show="calendarStatus[date] === 'partial' && !isSelected(date)"
                                                class="absolute bottom-1 w-1 h-1 bg-yellow-400 rounded-full shadow-sm"></span>
                                        </button>
                                    </div>
                                </template>
                            </div>


                            <div
                                class="mt-6 border-t border-gray-200 pt-4 flex flex-wrap justify-center gap-4 text-xs text-gray-400">
                                <div class="flex items-center gap-1"><span
                                        class="w-2 h-2 rounded-full bg-red-600"></span> Dipilih</div>
                                <div class="flex items-center gap-1"><span
                                        class="w-2 h-2 rounded-full border border-red-600"></span> Hari Ini</div>
                                <div class="flex items-center gap-1"><span
                                        class="w-2 h-2 rounded-full bg-yellow-400"></span> Terisi Sebagian</div>
                                <div class="flex items-center gap-1 p-2 bg-gray-200 rounded-md"><span
                                        class="text-gray-300 line-through">1</span>
                                    Penuh/Tutup</div>
                            </div>
                        </div>
                    </div>


                    {{-- Container Kanan: Pilihan Jam --}}
                    <div class="w-full md:flex-1 md:mt-[52px]"> {{-- Menyamakan tinggi dengan tombol kembali di kiri --}}
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 min-h-[450px] relative">
                            <div
                                class="flex flex-col sm:flex-row justify-between items-center mb-8 border-b border-gray-100 pb-4 gap-3 sm:gap-0">
                                <h3 class="text-md lg:text-xl font-bold text-gray-800">Pilih Jam Foto</h3>
                                <div x-show="formattedDate"
                                    class="w-full sm:w-auto text-center sm:text-right mt-1 sm:mt-0">
                                    <span x-text="formattedDate"
                                        class="text-xs lg:text-sm font-bold text-red-600 bg-red-50 px-4 py-2 rounded-lg border border-red-100 inline-block"></span>
                                </div>
                            </div>


                            <div x-cloak x-show="!selectedDateFull"
                                class="flex flex-col items-center justify-center h-64 text-gray-300">
                                <svg class="w-20 h-20 mb-4 opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="font-medium">Pilih tanggal di kalender dulu ya</p>
                            </div>


                            <div x-cloak x-show="isLoading"
                                class="absolute inset-0 bg-white/90 z-10 flex flex-col items-center justify-center rounded-3xl">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mb-3"></div>
                                <p class="text-gray-500 font-medium">Mengecek slot tersedia...</p>
                            </div>


                            <div x-cloak x-show="selectedDateFull && !isLoading">
                                <div x-show="slots.length > 0">
                                    <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3 mb-8">
                                        <template x-for="slot in slots" :key="slot.time">
                                            <button @click="selectTime(slot)"
                                                :disabled="!slot.is_available || (isLockedByOther(slot.time) && !selectedTimes
                                                    .includes(slot.time))"
                                                :class="{
                                                    'bg-red-600 text-white scale-105 shadow-md': selectedTimes.includes(
                                                        slot.time),
                                                    'bg-yellow-100 text-yellow-600 border-yellow-300 opacity-80 cursor-not-allowed': isLockedByOther(
                                                            slot.time) && !selectedTimes.includes(slot.time) && slot
                                                        .is_available,
                                                    'bg-white text-gray-700 border border-gray-200 hover:border-red-500 hover:text-red-600':
                                                        !selectedTimes.includes(slot.time) && !isLockedByOther(slot
                                                            .time) && slot.is_available,
                                                    'bg-gray-50 text-gray-300 cursor-not-allowed border-transparent': !
                                                        slot.is_available
                                                }"
                                                class="py-3 px-2 rounded-xl text-sm font-bold transition-all duration-200 flex flex-col items-center justify-center border relative group">
                                                <span x-text="slot.time" class="text-base"></span>
                                                <div x-cloak x-show="selectedTimes.includes(slot.time)"
                                                    @click.stop="deselectTime()"
                                                    class="absolute -top-2 -right-2 bg-red-800 hover:bg-gray-900 text-white rounded-full p-1 shadow-md cursor-pointer z-10">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </template>
                                    </div>


                                    <div class="pt-6 border-t border-gray-100">
                                        <form x-ref="bookingForm" action="{{ route('booking.store') }}"
                                            method="POST" @submit.prevent="submitBooking($event)">
                                            @csrf
                                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                                            <input type="hidden" name="date" x-model="selectedDateFull">
                                            <input type="hidden" name="time" x-model="selectedTime">
                                            <input type="hidden" name="session_id" :value="browserSessionId">
                                            <input type="hidden" name="total_pax" x-model="totalPax">


                                            <button type="submit" :disabled="!selectedTime"
                                                :class="!selectedTime ?
                                                    'opacity-50 cursor-not-allowed bg-gray-200 text-gray-400' :
                                                    'bg-red-600 hover:bg-red-700 text-white shadow-sm'"
                                                class="w-full py-4 rounded-xl font-bold text-base lg:text-lg transition-all duration-300 flex justify-center items-center">
                                                <span>Lanjut Pembayaran</span>
                                            </button>
                                        </form>


                                        {{-- MODAL KONFIRMASI --}}
                                        <template x-teleport="body">
                                            <div x-show="isBookingModalOpen"
                                                class="fixed inset-0 z-[99] flex items-center justify-center min-h-screen px-4 py-6 sm:px-0"
                                                style="display: none;">
                                                <div x-show="isBookingModalOpen"
                                                    class="fixed inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm"
                                                    @click="isBookingModalOpen = false"></div>
                                                <div x-show="isBookingModalOpen"
                                                    class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden z-10">
                                                    <div class="p-6 text-center">
                                                        <div
                                                            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                                            <svg class="h-6 w-6 text-red-600" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-bold text-gray-900">Konfirmasi Booking
                                                        </h3>
                                                        <div
                                                            class="mt-4 text-sm text-gray-600 text-left bg-gray-50 p-4 rounded-xl border border-gray-100">
                                                            <div class="flex justify-between mb-2">
                                                                <span>Tanggal:</span><span
                                                                    class="font-bold text-gray-900"
                                                                    x-text="formattedDate"></span>
                                                            </div>
                                                            <div class="flex justify-between mb-2">
                                                                <span>Jam:</span><span class="font-bold text-gray-900"
                                                                    x-text="selectedTime ? selectedTime + ' WIB' : ''"></span>
                                                            </div>
                                                            <div class="flex justify-between mb-2"><span>Jumlah
                                                                    Orang:</span><span class="font-bold text-gray-900"
                                                                    x-text="totalPax + ' Orang'"></span></div>
                                                            <hr class="my-3 border-gray-200">
                                                            <div class="flex justify-between items-center mb-1"><span
                                                                    class="font-medium text-gray-900">Total
                                                                    Harga:</span><span class="font-bold text-gray-900"
                                                                    x-text="formatRupiah(totalPrice)"></span></div>
                                                            <div class="flex justify-between items-center mb-1"><span
                                                                    class="font-medium text-gray-900">DP (Uang
                                                                    Muka):</span><span
                                                                    class="font-bold text-red-600">Rp
                                                                    50.000</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row gap-3">
                                                        <button @click="isBookingModalOpen = false" type="button"
                                                            class="w-full justify-center rounded-lg border border-gray-300 px-4 py-2 bg-white text-gray-700 font-medium hover:bg-gray-50">Batal</button>
                                                        <button @click="confirmBooking()" type="button"
                                                            class="w-full justify-center rounded-lg border border-transparent px-4 py-2 bg-red-600 text-white font-medium hover:bg-red-700">Ya,
                                                            Lanjut</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>


                                    </div>
                                </div>
                            </div>


                            <div x-cloak x-show="selectedDateFull && !isLoading && slots.length === 0"
                                class="text-center py-12">
                                <div class="bg-red-50 text-red-500 rounded-full p-3 inline-block mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-600 font-medium">Hari ini sudah tidak ada jadwal kosong.</p>
                            </div>


                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bookingSystem', (packageId, basePrice, maxPax, extraPrice) => ({
                step: 1,
                totalPax: 1,
                basePrice: parseInt(basePrice),
                maxPax: parseInt(maxPax),
                extraPrice: parseInt(extraPrice),
                totalPrice: 0,


                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ],
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
                no_of_days: [],
                blankDays: [],


                selectedDay: null,
                selectedTimes: [],
                packageDuration: {{ $package->duration_minutes }},


                selectedDateFull: null,
                selectedTime: null,
                formattedDate: null,


                slots: [],
                isLoading: false,
                calendarStatus: {},
                lockedSlots: [],


                browserSessionId: localStorage.getItem('alineas_session') || (function() {
                    let newId = 'sess_' + Math.random().toString(36).substr(2, 9);
                    localStorage.setItem('alineas_session', newId);
                    return newId;
                })(),
                currentUserId: {{ Auth::id() ?? 'null' }},
                isBookingModalOpen: false,


                init() {
                    this.calculateTotal();
                    this.calculateDays();
                    this.fetchCalendarStatus();


                    if (typeof Echo !== 'undefined') {
                        Echo.channel('alineas.calendar')
                            .listen('.SlotLocked', (e) => {
                                this.addLock(e.date, e.time, e.userId);
                            })
                            .listen('.SlotUnlocked', (e) => {
                                this.removeLock(e.date, e.time);
                            })
                            .listen('.BookingPaid', (e) => {
                                this.fetchCalendarStatus();
                                if (this.selectedDateFull === e.date) {
                                    this.fetchSlots(e.date);
                                    this.selectedTime = null;
                                    this.selectedTimes = [];
                                }
                            });
                    }
                },


                calculateTotal() {
                    let extraCount = Math.max(0, this.totalPax - this.maxPax);
                    this.totalPrice = this.basePrice + (extraCount * this.extraPrice);
                },
                incrementPax() {
                    if (this.totalPax < 15) {
                        this.totalPax++;
                        this.calculateTotal();
                    }
                },
                decrementPax() {
                    if (this.totalPax > 1) {
                        this.totalPax--;
                        this.calculateTotal();
                    }
                },
                nextStep() {
                    this.step = 2;
                },
                prevStep() {
                    this.step = 1;
                },
                formatRupiah(number) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
                },


                // KEMBALIKAN FUNGSI LOCKING YANG HILANG SEBELUMNYA KE SINI
                addLock(date, time, lockedById) {
                    if (lockedById == this.browserSessionId || (this.currentUserId && lockedById == this
                            .currentUserId)) return;
                    const exists = this.lockedSlots.some(l => l.date === date && l.time === time);
                    if (!exists) {
                        this.lockedSlots.push({
                            date,
                            time,
                            session_id: lockedById
                        });
                    }
                },
                removeLock(date, time) {
                    const cleanTargetDate = String(date).substring(0, 10).trim();
                    const cleanTargetTime = String(time).substring(0, 5).trim();
                    this.lockedSlots = this.lockedSlots.filter(l => {
                        const lDate = String(l.date).substring(0, 10).trim();
                        const lTime = String(l.time).substring(0, 5).trim();
                        return !(lDate === cleanTargetDate && lTime === cleanTargetTime);
                    });
                },
                isLockedByOther(time) {
                    return this.lockedSlots.some(l => l.date === this.selectedDateFull && l.time ===
                        time);
                },


                selectTime(slot) {
                    if (!slot.is_available) return;
                    const slotsNeeded = Math.max(1, Math.ceil(this.packageDuration / 30));
                    const startIndex = this.slots.findIndex(s => s.time === slot.time);


                    if (startIndex + slotsNeeded > this.slots.length) {
                        Swal.fire('Waktu Kurang',
                            'Sisa jam operasional tidak cukup untuk durasi paket ini.', 'warning');
                        return;
                    }


                    let timesToLock = [];
                    for (let i = 0; i < slotsNeeded; i++) {
                        let checkSlot = this.slots[startIndex + i];
                        if (!checkSlot.is_available || this.isLockedByOther(checkSlot.time)) {
                            Swal.fire('Bentrok',
                                'Beberapa jam yang beririsan sudah diambil orang lain.', 'warning');
                            return;
                        }
                        timesToLock.push(checkSlot.time);
                    }


                    const previousTimes = this.selectedTimes;
                    const previousTime = this.selectedTime;


                    this.selectedTimes = timesToLock;
                    this.selectedTime = slot.time;
                    this.lockedSlots = this.lockedSlots.filter(l => l.session_id !== this
                        .browserSessionId);


                    fetch('/api/lock-slot', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                package_id: packageId,
                                date: this.selectedDateFull,
                                time: slot.time,
                                session_id: this.browserSessionId
                            })
                        })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok || data.status === 'failed') throw new Error(data
                                .message || 'Gagal tersambung');
                        })
                        .catch(err => {
                            Swal.fire('Oops!', err.message, 'error');
                            this.selectedTimes = previousTimes;
                            this.selectedTime = previousTime;
                            this.fetchSlots(this.selectedDateFull);
                        });
                },
                deselectTime() {
                    const previousTimes = this.selectedTimes;
                    const previousTime = this.selectedTime;
                    this.selectedTimes = [];
                    this.selectedTime = null;


                    fetch('/api/unlock-all', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                session_id: this.browserSessionId
                            })
                        })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok || data.status === 'failed') throw new Error(
                                'Gagal membatalkan');
                        })
                        .catch(err => {
                            Swal.fire('Oops!', 'Gagal membatalkan pilihan.', 'error');
                            this.selectedTimes = previousTimes;
                            this.selectedTime = previousTime;
                        });
                },
                selectDate(date) {
                    if (this.isPastDate(date) || this.calendarStatus[date] === 'full') return;


                    this.selectedDay = date;
                    const monthStr = (this.month + 1).toString().padStart(2, '0');
                    const dateStr = date.toString().padStart(2, '0');


                    this.selectedDateFull = `${this.year}-${monthStr}-${dateStr}`;
                    this.selectedTime = null;
                    this.selectedTimes = [];


                    const d = new Date(this.year, this.month, date);
                    this.formattedDate = d.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });


                    this.fetchSlots(this.selectedDateFull);
                },
                fetchSlots(date) {
                    this.isLoading = true;
                    this.slots = [];
                    Promise.all([
                        fetch(`/api/check-slots/${packageId}?date=${date}`).then(r => r.json()),
                        fetch(`/api/get-active-locks/${packageId}?date=${date}`).then(r => r
                            .json())
                    ]).then(([slotsData, locksData]) => {
                        this.slots = slotsData;
                        const othersLocks = locksData.filter(l => {
                            let isMine = (l.session_id === this.browserSessionId) || (
                                this.currentUserId && l.user_id === this
                                .currentUserId);
                            return !isMine;
                        });
                        this.lockedSlots = this.lockedSlots.filter(l => l.date !== date).concat(
                            othersLocks.map(l => ({
                                date: date,
                                time: l.time,
                                session_id: l.session_id
                            })));
                        this.isLoading = false;
                    }).catch(err => {
                        this.isLoading = false;
                    });
                },
                calculateDays() {
                    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    let dayOfWeek = new Date(this.year, this.month, 1).getDay();
                    let blankdaysArray = [];
                    let offset = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
                    for (let i = 1; i <= offset; i++) blankdaysArray.push(i);
                    let daysArray = [];
                    for (let i = 1; i <= daysInMonth; i++) daysArray.push(i);
                    this.blankDays = blankdaysArray;
                    this.no_of_days = daysArray;
                },
                changeMonth(val) {
                    this.month += val;
                    if (this.month > 11) {
                        this.month = 0;
                        this.year++;
                    } else if (this.month < 0) {
                        this.month = 11;
                        this.year--;
                    }
                    this.calculateDays();
                    this.fetchCalendarStatus();
                },
                fetchCalendarStatus() {
                    fetch(`/api/check-calendar/${packageId}?year=${this.year}&month=${this.month}`)
                        .then(r => r.json()).then(data => {
                            this.calendarStatus = data;
                        });
                },
                isToday(date) {
                    const today = new Date();
                    return date === today.getDate() && this.month === today.getMonth() && this.year ===
                        today.getFullYear();
                },
                isPastDate(date) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const currentCheck = new Date(this.year, this.month, date);
                    return currentCheck < today;
                },
                isSelected(date) {
                    return this.selectedDay === date;
                },
                submitBooking(e) {
                    if (!this.selectedDateFull || !this.selectedTime) return;
                    this.isBookingModalOpen = true;
                },
                confirmBooking() {
                    window.isSubmittingForm = true;
                    this.$refs.bookingForm.submit();
                },
            }));
        });


        // Trigger deteksi user menutup tab atau me-refresh
        window.addEventListener('beforeunload', function(e) {
            if (window.isSubmittingForm) return;
            let sessionId = localStorage.getItem('alineas_session');
            if (sessionId) {
                fetch('/api/unlock-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        session_id: sessionId
                    }),
                    keepalive: true
                });
            }
        });
    </script>
</x-app-layout>
