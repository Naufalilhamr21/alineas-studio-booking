<?php
use App\Http\Controllers\API\MidtransCallbackController;
use Illuminate\Support\Facades\Route;

// Endpoint yang akan ditembak oleh Midtrans
Route::post('/midtrans-callback', [MidtransCallbackController::class, 'callback']);