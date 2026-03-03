<?php

namespace App\Http\Controllers\API;

use App\Events\BookingPaid;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
   public function callback()
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = config('services.midtrans.is_production') ?? env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // // --- TAMBAHAN MANTRA BYPASS SSL LOKAL ---
        // Config::$curlOptions = [
        //     CURLOPT_SSL_VERIFYHOST => 0,
        //     CURLOPT_SSL_VERIFYPEER => 0,
        //     CURLOPT_HTTPHEADER => [],
        // ];
        // // ----------------------------------------

        // 2. Buat Instance Notifikasi
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            // Jika kamu cek log Midtrans, pasti error-nya muncul dari sini sebelumnya
            return response()->json(['message' => 'Notification Error', 'error' => $e->getMessage()], 500);
        }

        // 3. Ambil Data Transaksi
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id; 

        // 4. Cari Booking Berdasarkan Order ID
        $booking = Booking::where('booking_code', $order_id)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Simpan status lama untuk dibandingkan nanti
        $oldStatus = $booking->status;

        // 5. Logika Update Status Berdasarkan Respon Midtrans
        if ($status == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $booking->update(['status' => 'unpaid']);
                } else {
                    $booking->update(['status' => 'paid']);
                }
            }
        } else if ($status == 'settlement') {
            // Sukses Bayar (Transfer, GoPay, dll)
            $booking->update(['status' => 'paid']);
        } else if ($status == 'pending') {
            $booking->update(['status' => 'unpaid']);
        } else if ($status == 'deny' || $status == 'expire' || $status == 'cancel') {
            // Gagal / Expired / Dibatalkan
            $booking->update(['status' => 'cancelled']);
        }

        // -------------------------------------------------------------------
        // 6. BROADCAST KE FRONTEND JIKA ADA PERUBAHAN STATUS PENTING
        // -------------------------------------------------------------------
        
        // Jika status berubah menjadi LUNAS atau DIBATALKAN
        if ($oldStatus !== $booking->status && in_array($booking->status, ['paid', 'cancelled'])) {
            
            // Ambil tanggal lokal (WIB) dari start_time UTC di database
            $dateWib = \Carbon\Carbon::parse($booking->start_time)
                        ->setTimezone('Asia/Jakarta')
                        ->format('Y-m-d');
            
            // Pancarkan event agar kalender & jam me-refresh datanya otomatis!
            broadcast(new BookingPaid($dateWib));
        }

        return response()->json(['message' => 'Callback received successfully']);
    }
}