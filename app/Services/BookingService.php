<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLock;
use Illuminate\Support\Carbon;

class BookingService
{
    protected $timezone = 'Asia/Jakarta';
    protected $openHour = '11:00';
    protected $closeHour = '18:00';

    // Logika untuk API Check Slots
    public function getAvailableSlots($date, $packageId, $durationMinutes)
    {
        $startQuery = Carbon::createFromFormat('Y-m-d', $date, $this->timezone)->subDay()->startOfDay()->setTimezone('UTC');
        $endQuery = Carbon::createFromFormat('Y-m-d', $date, $this->timezone)->addDay()->endOfDay()->setTimezone('UTC');

        $existingBookings = Booking::where('status', 'paid')
            ->where('start_time', '<', $endQuery)
            ->where('end_time', '>', $startQuery)
            ->get();

        $openTime = Carbon::createFromFormat('Y-m-d H:i', "$date {$this->openHour}", $this->timezone);
        $closeTime = Carbon::createFromFormat('Y-m-d H:i', "$date {$this->closeHour}", $this->timezone);
        $now = Carbon::now($this->timezone);

        // // Ambil semua lock aktif untuk paket & tanggal ini dalam satu query
        // $activeLocks = BookingLock::where('package_id', $packageId)
        //     ->where('date', $date)
        //     ->where('expires_at', '>', $now)
        //     ->pluck('time')
        //     ->toArray();

        $slots = [];
        $currentSlot = $openTime->copy();

        while ($currentSlot->lt($closeTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($durationMinutes);
            if ($slotEnd->gt($closeTime)) break;

            $isAvailable = true;
            $slotTimeString = $currentSlot->format('H:i');

            // 1. Cek bentrok dengan booking asli
            foreach ($existingBookings as $booking) {
                $bStart = Carbon::parse($booking->start_time)->setTimezone($this->timezone);
                $bEnd = Carbon::parse($booking->end_time)->setTimezone($this->timezone);

                if ($currentSlot->lt($bEnd) && $slotEnd->gt($bStart)) {
                    $isAvailable = false;
                    break; 
                }
            }

            // 2. Cek jam lewat
            if ($openTime->isSameDay($now) && $currentSlot->lt($now)) {
                $isAvailable = false;
            }

            // // 3. Cek locks (menggunakan array yang sudah di-fetch, menghemat query database)
            // if (in_array($slotTimeString, $activeLocks)) {
            //     $isAvailable = false;
            // }

            $slots[] = [
                'time' => $slotTimeString,
                'is_available' => $isAvailable,
            ];

            $currentSlot->addMinutes(30);
        }

        return $slots;
    }

    // Logika untuk API Check Calendar
    public function getCalendarMonthStatus($year, $month, $durationMinutes)
    {
        $startOfMonth = Carbon::createFromDate($year, $month, 1, $this->timezone)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $startUtc = $startOfMonth->copy()->setTimezone('UTC');
        $endUtc = $endOfMonth->copy()->setTimezone('UTC');

        $bookings = Booking::where('status', 'paid') 
            ->whereBetween('start_time', [$startUtc, $endUtc])
            ->get();

        // OPTIMASI PERFORMA: Kelompokkan booking berdasarkan tanggal (Mencegah N+1 loop process)
        $bookingsByDate = $bookings->groupBy(function ($b) {
            return Carbon::parse($b->start_time)->setTimezone($this->timezone)->format('Y-m-d');
        });

        $calendarData = [];
        $currentDate = $startOfMonth->copy();
        $now = Carbon::now($this->timezone);

        while ($currentDate->lte($endOfMonth)) {
            $day = $currentDate->day;
            $status = 'available';
            $currentDateString = $currentDate->format('Y-m-d');

            // Ambil booking hanya untuk hari ini dengan cepat
            $dayBookings = $bookingsByDate->get($currentDateString, collect());

            $openTime = $currentDate->copy()->setTime(11, 0, 0);
            $closeTime = $currentDate->copy()->setTime(18, 0, 0);
            
            $totalSlots = 0;
            $realBookedSlots = 0;
            $slotCursor = $openTime->copy();

            while($slotCursor->lt($closeTime)) {
                $slotEnd = $slotCursor->copy()->addMinutes($durationMinutes);
                if($slotEnd->gt($closeTime)) break;

                $totalSlots++;
                $isRealBooking = false;

                foreach($dayBookings as $booking) {
                    $bStart = Carbon::parse($booking->start_time)->setTimezone($this->timezone);
                    $bEnd = Carbon::parse($booking->end_time)->setTimezone($this->timezone);

                    if ($slotCursor->lt($bEnd) && $slotEnd->gt($bStart)) {
                        $isRealBooking = true;
                        break;
                    }
                }

                if($isRealBooking) {
                    $realBookedSlots++;
                }

                $slotCursor->addMinutes(30);
            }

            if ($currentDate->endOfDay()->lt($now)) {
                $status = 'past';
            } elseif ($totalSlots > 0 && $realBookedSlots >= $totalSlots) {
                $status = 'full';
            } elseif ($realBookedSlots > 0) {
                $status = 'partial'; 
            }

            $calendarData[$day] = $status;
            $currentDate->addDay();
        }

        return $calendarData;
    }
}