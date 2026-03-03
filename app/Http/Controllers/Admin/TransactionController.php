<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Ambil data booking dengan relasi user & package
        // Urutkan dari yang terbaru
        $bookings = Booking::with(['user', 'package'])->latest()->get();

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
        $booking = \App\Models\Booking::findOrFail($id);

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

        $booking = \App\Models\Booking::findOrFail($id);
        $booking->update([
            'google_drive_link' => $request->google_drive_link
        ]);

        return back()->with('success', 'Link Google Drive berhasil disimpan!');
    }
}