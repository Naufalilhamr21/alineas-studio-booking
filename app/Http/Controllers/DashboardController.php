<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.dashboard');
        }
        // Kalau customer buka /dashboard, lempar ke home
        return redirect()->route('home');
    }

    // Method KHUSUS untuk halaman History/Riwayat
    public function history()
    {
        $user = Auth::user();

        // Ambil data booking
        $bookings = Booking::with('package')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Tampilkan view yang berisi tabel (gunakan file dashboard.blade.php yang lama sebagai history)
        return view('customer.history', compact('bookings')); 
    }
}