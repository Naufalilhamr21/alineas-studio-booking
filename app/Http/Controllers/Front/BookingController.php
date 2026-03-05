<?php

namespace App\Http\Controllers\Front;

use App\Events\BookingPaid;
use App\Events\NewBookingCreated;
use App\Http\Controllers\Controller;
use App\Mail\AdminNewBookingMail;
use App\Models\Booking;
use App\Models\BookingLock;
use App\Models\Package;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    protected $bookingService;

    // Inject BookingService melalui Constructor
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function show(Package $package)
    {
        return view('front.booking', compact('package'));
    }

    public function checkSlots(Request $request, Package $package)
    {
        if (!$request->has('date')) return response()->json([]);

        $slots = $this->bookingService->getAvailableSlots(
            $request->input('date'), 
            $package->id, 
            $package->duration_minutes
        );

        return response()->json($slots);
    }

    public function checkCalendar(Request $request, Package $package)
    {
        $year = $request->input('year');
        $month = $request->input('month') + 1; 

        $calendarData = $this->bookingService->getCalendarMonthStatus(
            $year, 
            $month, 
            $package->duration_minutes
        );

        return response()->json($calendarData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $package = Package::findOrFail($request->package_id);
        $user = Auth::user();

        $dpAmount = 50000;

        // (Opsional/Keamanan) Jika kebetulan harga paket di bawah 50rb, DP disamakan dengan harga paket
        if ($package->price < $dpAmount) {
            $dpAmount = $package->price;
        }

        $remainingBalance = $package->price - $dpAmount;

        $startTime = Carbon::createFromFormat('Y-m-d H:i', "{$request->date} {$request->time}", 'Asia/Jakarta');
        $endTime = $startTime->copy()->addMinutes($package->duration_minutes);

        $startUtc = $startTime->copy()->setTimezone('UTC');
        $endUtc = $endTime->copy()->setTimezone('UTC');

        // Cek Bentrok
        $conflict = Booking::where(function($query) {
                $query->where('status', 'paid')
                      ->orWhere(function($q) {
                          $q->where('status', 'unpaid')
                            ->where('created_at', '>=', now()->subMinutes(15));
                      });
            })
            ->where('start_time', '<', $endUtc)
            ->where('end_time', '>', $startUtc)
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Maaf, slot waktu ini SUDAH TERISI. Silakan pilih jam lain.');
        }

        $booking = Booking::create([
            'booking_code' => 'ALN-' . strtoupper(Str::random(6)),
            'user_id' => $user->id,
            'package_id' => $package->id,
            'start_time' => $startUtc,
            'end_time' => $endUtc,
            'total_price' => $package->price,
            'dp_amount' => $dpAmount,
            'remaining_balance' => $remainingBalance,
            'status' => 'unpaid',
        ]);

        // NewBookingCreated::dispatch($booking);

        // --- KODE KIRIM EMAIL ---
        // try {
        //     // Ganti alamat ini dengan email yang akan MENERIMA notifikasi (bisa sama dengan pengirim)
        //     Mail::to('alineasstudio@gmail.com')->send(new AdminNewBookingMail($booking));
        // } catch (\Exception $e) {
        //     // Tangkap error jika email gagal agar tidak merusak proses checkout user
        //     \Illuminate\Support\Facades\Log::error('Gagal kirim email notif: ' . $e->getMessage());
        // }
        // // --- AKHIR KODE EMAIL ---

        // Hapus Kuncian
        $slotsNeeded = max(1, ceil($package->duration_minutes / 30));
        $timesToDelete = [];
        $timeCursor = \Carbon\Carbon::parse($request->time);
        
        for ($i = 0; $i < $slotsNeeded; $i++) {
            $timesToDelete[] = $timeCursor->copy()->addMinutes($i * 30)->format('H:i');
        }

        \App\Models\BookingLock::where('date', $request->date)
            ->whereIn('time', $timesToDelete)
            ->delete();

        // Setup Midtrans
        Config::$serverKey = config('services.midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = config('services.midtrans.is_production') ?? env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // // --- UNTUK BYPASS SSL MIDTRANS DI LOKAL ---
        // Config::$curlOptions = [
        //     CURLOPT_SSL_VERIFYHOST => 0,
        //     CURLOPT_SSL_VERIFYPEER => 0,
        //     CURLOPT_HTTPHEADER => [], // <-- TAMBAHAN KUNCI PEMECAM MASALAH
        // ];
        // // --------------------------------------------------------------

        // 5. Sesuaikan Parameter Midtrans (Tagih HANYA nominal DP)
        $midtransParams = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => $dpAmount, // Yang ditagihkan adalah nominal DP
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'DP-' . $package->id,
                    'price' => (int) $dpAmount, // Harga item disamakan dengan nominal DP
                    'quantity' => 1,
                    'name' => 'DP Booking - ' . $package->name, // Nama item diubah agar user paham ini hanya DP
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($midtransParams);
            $booking->update(['snap_token' => $snapToken]);
            return redirect()->route('booking.payment', $booking->id);
        } catch (\Exception $e) {
            $booking->delete();
            return back()->with('error', 'Gagal payment gateway: ' . $e->getMessage());
        }
    }

    public function lockSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'session_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => 'Validasi Gagal'], 422);
        }

        $packageId = $request->input('package_id');
        $date = $request->input('date');
        $time = $request->input('time');
        $sessionId = $request->input('session_id');
        $userId = Auth::id();

        $package = Package::find($packageId);
        $slotsNeeded = max(1, ceil($package->duration_minutes / 30));
        $timesToLock = [];
        $startTime = Carbon::createFromFormat('H:i', $time);

        for ($i = 0; $i < $slotsNeeded; $i++) {
            $timesToLock[] = $startTime->copy()->addMinutes($i * 30)->format('H:i');
        }

        $broadcastData = DB::transaction(function() use ($userId, $sessionId, $package, $timesToLock, $date) {
            $unlockedSlots = [];
            $lockedSlots = [];

            $existingLocks = BookingLock::where('date', $date)->whereIn('time', $timesToLock)->get();
            foreach ($existingLocks as $lock) {
                $isOwnLock = ($userId && $lock->user_id == $userId) || ($lock->session_id == $sessionId);
                if (!$isOwnLock && $lock->expires_at > now()) {
                    response()->json(['status' => 'failed', 'message' => 'Slot beririsan sudah diambil'], 409)->send();
                    exit; 
                }
            }

            $oldLocksQuery = BookingLock::where('session_id', $sessionId)
                ->where(function($query) use ($date, $timesToLock) {
                    $query->where('date', '!=', $date)
                        ->orWhereNotIn('time', $timesToLock);
                });

            $oldLocks = $oldLocksQuery->get();

            foreach ($oldLocks as $lock) {
                $unlockedSlots[] = [
                    'package_id' => $lock->package_id,
                    'date' => Carbon::parse($lock->date)->format('Y-m-d'),
                    'time' => $lock->time,
                    'session_id' => $sessionId,
                    'userId' => $sessionId 
                ];
            }

            $oldLocksQuery->delete();

            BookingLock::where('session_id', $sessionId)
                ->where('date', $date)
                ->whereIn('time', $timesToLock)
                ->delete();

            foreach ($timesToLock as $timeBlock) {
                BookingLock::create([
                    'package_id' => $package->id,
                    'date' => $date,
                    'time' => $timeBlock,
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'expires_at' => now()->addMinutes(5),
                ]);
                
                $lockedSlots[] = [
                    'package_id' => $package->id,
                    'date' => $date,
                    'time' => $timeBlock,
                    'session_id' => $sessionId,
                    'userId' => $sessionId 
                ];
            }

            return ['unlocked' => $unlockedSlots, 'locked' => $lockedSlots];
        });

        foreach ($broadcastData['unlocked'] as $u) {
            broadcast(new \App\Events\SlotUnlocked($u['package_id'], $u['date'], $u['time'], $u['session_id']));
        }

        foreach ($broadcastData['locked'] as $l) {
            broadcast(new \App\Events\SlotLocked($l['package_id'], $l['date'], $l['time'], $l['session_id']));
        }

        return response()->json(['status' => 'success']);
    }

    public function unlockAll(Request $request)
    {
        $sessionId = $request->input('session_id');
        $userId = Auth::id();
        
        $query = BookingLock::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else if ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return response()->json(['status' => 'no_session_provided']);
        }
        
        $locks = $query->get();
        
        foreach($locks as $lock) {
            broadcast(new \App\Events\SlotUnlocked($lock->package_id, $lock->date, $lock->time, $lock->session_id));
            $lock->delete();
        }
        
        return response()->json(['status' => 'cleared']);
    }

    public function getActiveLocks($packageId, Request $request)
    {
        // 1. Bersihkan lock biasa yang sudah expired
        BookingLock::where('expires_at', '<', now())->delete();

        // 2. Ambil lock dari user yang sedang milih-milih jam (Kuning Biasa)
        $locks = BookingLock::where('date', $request->date)
            ->where('expires_at', '>', now())
            ->get(['time', 'user_id', 'session_id'])
            ->toArray();

        // 3. Ambil booking UNPAID yang umurnya kurang dari 15 menit (Kuning Menunggu Pembayaran)
        $timezone = 'Asia/Jakarta';
        $startOfDay = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date, $timezone)->startOfDay()->setTimezone('UTC');
        $endOfDay = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date, $timezone)->endOfDay()->setTimezone('UTC');

        $unpaidBookings = Booking::where('status', 'unpaid')
            ->whereBetween('start_time', [$startOfDay, $endOfDay])
            ->where('created_at', '>=', now()->subMinutes(15)) // WAKTU TUNGGU MIDTRANS: 15 Menit
            ->get();

        foreach ($unpaidBookings as $ub) {
            $package = Package::find($ub->package_id);
            $duration = $package ? $package->duration_minutes : 30;
            $slotsNeeded = max(1, ceil($duration / 30));
            
            $slotTime = \Carbon\Carbon::parse($ub->start_time)->setTimezone($timezone);
            for ($i = 0; $i < $slotsNeeded; $i++) {
                $locks[] = [
                    'time' => $slotTime->copy()->addMinutes($i * 30)->format('H:i'),
                    'user_id' => $ub->user_id,
                    'session_id' => 'booking_pending_' . $ub->id // ID palsu agar terbaca sebagai orang lain
                ];
            }
        }

        return response()->json($locks);
    }

    public function payment(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);
        if ($booking->status === 'paid') return redirect()->route('dashboard')->with('success', 'Lunas!');
        return view('front.payment', compact('booking'));
    }
}