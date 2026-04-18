<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // 1. Ambil jadwal mendatang
            $upcomingBookings = \App\Models\Booking::with(['user', 'package'])
                ->whereIn('status', ['paid', 'dp'])
                ->where('start_time', '>=', now())
                ->orderBy('start_time', 'asc')
                ->take(5)
                ->get();

            // 2. Hitung Total Pendapatan KESELURUHAN
            $totalPendapatan = \App\Models\Booking::whereIn('status', ['paid', 'dp'])
                ->selectRaw('SUM(total_price - remaining_balance) as total_revenue')
                ->value('total_revenue') ?? 0;
                
            // 3. Hitung statistik untuk BULAN INI
            $startOfMonth = \Illuminate\Support\Carbon::now()->startOfMonth();
            $endOfMonth = \Illuminate\Support\Carbon::now()->endOfMonth();

            // Pendapatan bulan ini
            $pendapatanBulanIni = \App\Models\Booking::whereIn('status', ['paid', 'dp'])
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->selectRaw('SUM(total_price - remaining_balance) as total_revenue')
                ->value('total_revenue') ?? 0;

            // Total transaksi bulan ini
            $transaksiBulanIni = \App\Models\Booking::whereIn('status', ['paid', 'dp'])
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
            // Kirim SEMUA variabel ke view
            return view('admin.dashboard', compact(
                'upcomingBookings', 
                'totalPendapatan', 
                'pendapatanBulanIni', 
                'transaksiBulanIni'
            ));
        }
        
        // Kalau customer buka /dashboard, lempar ke home
        return redirect()->route('home');
    }

    public function history()
    {
        $user = Auth::user();

        $bookings = Booking::with('package')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('customer.history', compact('bookings'));
    }
}