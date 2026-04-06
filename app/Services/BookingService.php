<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\StudioSchedule; // Model Baru
use Illuminate\Support\Carbon;

class BookingService
{
    protected $timezone = 'Asia/Jakarta';
    protected $openHour = '11:00'; // Jam Buka Default
    protected $closeHour = '18:00'; // Jam Tutup Default

    public function getAvailableSlots($date, $packageId, $durationMinutes)
    {
        // 1. Cek apakah ada jadwal khusus pada tanggal ini
        $schedule = StudioSchedule::where('date', $date)->first();

        if ($schedule && $schedule->is_closed) {
            return []; // Jika ditandai tutup full, tidak ada slot
        }

        // 2. Tentukan jam buka & tutup (Gunakan jadwal khusus jika ada, jika tidak gunakan default)
        $actualOpenHour = ($schedule && $schedule->open_time) ? Carbon::parse($schedule->open_time)->format('H:i') : $this->openHour;
        $actualCloseHour = ($schedule && $schedule->close_time) ? Carbon::parse($schedule->close_time)->format('H:i') : $this->closeHour;

        $startQuery = Carbon::createFromFormat('Y-m-d', $date, $this->timezone)->subDay()->startOfDay()->setTimezone('UTC');
        $endQuery = Carbon::createFromFormat('Y-m-d', $date, $this->timezone)->addDay()->endOfDay()->setTimezone('UTC');

        $existingBookings = Booking::where('status', 'paid')
            ->where('start_time', '<', $endQuery)
            ->where('end_time', '>', $startQuery)
            ->get();

        $openTime = Carbon::createFromFormat('Y-m-d H:i', "$date {$actualOpenHour}", $this->timezone);
        $closeTime = Carbon::createFromFormat('Y-m-d H:i', "$date {$actualCloseHour}", $this->timezone);
        $now = Carbon::now($this->timezone);

        $slots = [];
        $currentSlot = $openTime->copy();

        while ($currentSlot->lt($closeTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($durationMinutes);
            if ($slotEnd->gt($closeTime)) break;

            $isAvailable = true;
            $slotTimeString = $currentSlot->format('H:i');

            foreach ($existingBookings as $booking) {
                $bStart = Carbon::parse($booking->start_time)->setTimezone($this->timezone);
                $bEnd = Carbon::parse($booking->end_time)->setTimezone($this->timezone);

                if ($currentSlot->lt($bEnd) && $slotEnd->gt($bStart)) {
                    $isAvailable = false;
                    break; 
                }
            }

            if ($openTime->isSameDay($now) && $currentSlot->lt($now)) {
                $isAvailable = false;
            }

            $slots[] = [
                'time' => $slotTimeString,
                'is_available' => $isAvailable,
            ];

            $currentSlot->addMinutes(30);
        }

        return $slots;
    }

    public function getCalendarMonthStatus($year, $month, $durationMinutes)
    {
        $startOfMonth = Carbon::createFromDate($year, $month, 1, $this->timezone)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $startUtc = $startOfMonth->copy()->setTimezone('UTC');
        $endUtc = $endOfMonth->copy()->setTimezone('UTC');

        // Ambil semua pengaturan jadwal khusus bulan ini dan jadikan array (Key: Y-m-d)
        $customSchedules = StudioSchedule::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        $bookings = Booking::where('status', 'paid') 
            ->whereBetween('start_time', [$startUtc, $endUtc])
            ->get();

        $bookingsByDate = $bookings->groupBy(function ($b) {
            return Carbon::parse($b->start_time)->setTimezone($this->timezone)->format('Y-m-d');
        });

        $calendarData = [];
        $currentDate = $startOfMonth->copy();
        $now = Carbon::now($this->timezone);

        while ($currentDate->lte($endOfMonth)) {
            $day = $currentDate->day;
            $currentDateString = $currentDate->format('Y-m-d');

            // Cek apakah tanggal ini memiliki pengaturan jadwal khusus
            $dailySchedule = $customSchedules->get($currentDateString);

            if ($dailySchedule && $dailySchedule->is_closed) {
                $calendarData[$day] = 'full'; // Corek di kalender jika libur
                $currentDate->addDay();
                continue; 
            }

            // Tentukan jam operasional hari ini
            $actualOpenHour = ($dailySchedule && $dailySchedule->open_time) ? Carbon::parse($dailySchedule->open_time)->format('H:i') : $this->openHour;
            $actualCloseHour = ($dailySchedule && $dailySchedule->close_time) ? Carbon::parse($dailySchedule->close_time)->format('H:i') : $this->closeHour;

            $openParts = explode(':', $actualOpenHour);
            $closeParts = explode(':', $actualCloseHour);

            $openTime = $currentDate->copy()->setTime($openParts[0], $openParts[1], 0);
            $closeTime = $currentDate->copy()->setTime($closeParts[0], $closeParts[1], 0);

            $dayBookings = $bookingsByDate->get($currentDateString, collect());
            
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

            $status = 'available';
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