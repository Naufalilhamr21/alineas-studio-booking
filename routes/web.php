<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Front\BookingController;
use App\Http\Controllers\Admin\TransactionController;

/*
|--------------------------------------------------------------------------
| 1. ROUTE PUBLIC (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/pricelist', [LandingController::class, 'pricelist'])->name('pricelist');
Route::get('/package/{package:slug}', [LandingController::class, 'show'])->name('package.show');
Route::get('/gallery', [LandingController::class, 'gallery'])->name('gallery');

/*
|--------------------------------------------------------------------------
| 2. ROUTE AUTH (Harus Login: Customer & Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Router (Mengarahkan Admin ke dashboard admin, User ke history)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman Riwayat Booking Customer
    Route::get('/riwayat-booking', [DashboardController::class, 'history'])->name('booking.history');
    
    // Profile User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | 3. ROUTE BOOKING FLOW (Customer)
    |--------------------------------------------------------------------------
    */
    // Halaman Form Booking (Kalender)
    // PERBAIKAN: Nama route disesuaikan dengan view package-detail ('front.booking')
    Route::get('/booking/{package:slug}', [BookingController::class, 'show'])->name('front.booking'); 
    
    // Simpan Booking
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    
    // Halaman Pembayaran
    Route::get('/booking/payment/{booking}', [BookingController::class, 'payment'])->name('booking.payment');
    
    // API Cek Slot (Ajax)
    Route::get('/api/check-slots/{package}', [BookingController::class, 'checkSlots'])->name('api.check_slots');
    Route::get('/api/check-calendar/{package}', [BookingController::class, 'checkCalendar'])->name('api.check_calendar');

    // API UNTUK REAL-TIME LOCKING (Reverb)
    Route::post('/api/lock-slot', [BookingController::class, 'lockSlot'])->name('api.lock_slot');
    Route::get('/api/get-active-locks/{package}', [BookingController::class, 'getActiveLocks'])->name('api.get_active_locks');
    Route::post('/api/unlock-all', [BookingController::class, 'unlockAll'])->name('api.unlock_all');

    /*
    |--------------------------------------------------------------------------
    | 4. ROUTE ADMIN (Hanya Role Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Admin (Statistik)
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // CRUD: Packages & Galleries
        Route::resource('packages', PackageController::class);
        Route::resource('galleries', GalleryController::class);
        
        // Transaksi
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions/{booking}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::delete('/transactions/{booking}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
        Route::post('/transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
        Route::post('/transactions/{transaction}/drive', [TransactionController::class, 'updateDrive'])->name('transactions.drive');

        // Reschedule
        Route::post('/bookings/{booking}/reschedule', [TransactionController::class, 'reschedule'])->name('bookings.reschedule');
    });

});

require __DIR__.'/auth.php';