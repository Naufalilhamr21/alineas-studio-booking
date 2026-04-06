<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\BookingPaid; // Wajib untuk refresh kalender realtime

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // 1. Buat dasar query (mengambil relasi user dan package agar efisien)
        $query = Booking::with(['user', 'package'])->latest();

        // 2. Cek apakah ada parameter pencarian (search) dari input form admin
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                // Cari berdasarkan Kode Booking (kasus tidak sensitif huruf besar/kecil)
                $q->where('booking_code', 'like', '%' . $search . '%')
                  // ATAU cari berdasarkan nama User yang berelasi
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // 3. Eksekusi query dengan pagination (10 data per halaman)
        // Gunakan $bookings (bukan get()) agar fungsi pagination bekerja di Blade
        $bookings = $query->paginate(10);

        // Jika pencarian dilakukan tapi hasilnya kosong, kita bisa memberitahu admin
        // (Ini akan ditangani di file Blade bagian @empty)
        
        return view('admin.transactions.index', compact('bookings'));
    }

    // Fitur Approve Manual (Opsional, jika user bayar cash di lokasi)
    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'paid']);
        return back()->with('success', 'Booking berhasil disetujui!');
    }

    // Fitur Cancel Manual
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Booking berhasil dihapus!');
    }

    public function complete($id)
    {
        $booking = Booking::findOrFail($id);

        // Ubah sisa tagihan menjadi 0
        $booking->update([
            'remaining_balance' => 0
        ]);

        return back()->with('success', 'Pelunasan berhasil dikonfirmasi. Status sekarang sepenuhnya LUNAS.');
    }

    public function updateDrive(Request $request, $id)
    {
        $request->validate([
            'google_drive_link' => 'required|url' // Wajib diisi dan harus format URL/Link
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'google_drive_link' => $request->google_drive_link
        ]);

        return back()->with('success', 'Link Google Drive berhasil disimpan!');
    }

    // --- FUNGSI RESCHEDULE ---
    public function reschedule(Request $request, $id)
    {
        // 1. Validasi input dari Admin (Tanggal & Jam Baru)
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $booking = Booking::findOrFail($id);
        $package = $booking->package;
        
        $startTime = Carbon::createFromFormat('Y-m-d H:i', "{$request->date} {$request->time}", 'Asia/Jakarta');
        $endTime = $startTime->copy()->addMinutes($package->duration_minutes);
        
        $startUtc = $startTime->copy()->setTimezone('UTC');
        $endUtc = $endTime->copy()->setTimezone('UTC');

        // 2. Cek Bentrok (Kecualikan ID jadwal lama milik pelanggan ini sendiri)
        $conflict = Booking::where('id', '!=', $booking->id)
            ->where(function($query) {
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
            // Jika Admin milih jam yang ternyata sudah ada isinya
            return back()->with('error', 'Gagal Reschedule: Jadwal baru bentrok dengan pelanggan lain.');
        }

        // 3. Simpan tanggal lama sebelum ditimpa (untuk refresh kalender publik)
        $oldDateWib = Carbon::parse($booking->start_time)->setTimezone('Asia/Jakarta')->format('Y-m-d');
        
        // 4. Update data di database
        $booking->update([
            'start_time' => $startUtc,
            'end_time' => $endUtc,
        ]);

        // 5. Beritahu Publik via Pusher (Magic!)
        // Refresh tanggal lama agar kembali KOSONG
        broadcast(new BookingPaid($oldDateWib));
        
        // Refresh tanggal baru agar langsung TERKUNCI
        $newDateWib = $startTime->format('Y-m-d');
        if ($oldDateWib !== $newDateWib) {
            broadcast(new BookingPaid($newDateWib));
        }

        return back()->with('success', 'Jadwal pelanggan berhasil dipindahkan!');
    }
}