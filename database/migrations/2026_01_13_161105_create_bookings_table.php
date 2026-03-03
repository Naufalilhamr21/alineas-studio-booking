<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();

            // Relasi
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->OnDelete('cascade');
            
            // Waktu Booking
            $table->dateTime('start_time'); 
            $table->dateTime('end_time');
            
            // Keuangan
            $table->decimal('total_price', 12, 2);
            $table->decimal('dp_amount', 12, 2)->default(0); // Uang muka yang dibayar via Midtrans
            $table->decimal('remaining_balance', 12, 2)->default(0); // Sisa pelunasan di studio

            // Status
            $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('unpaid');

            // Snap Token Midtrans
            $table->string('snap_token')->nullable(); // Midtrans token
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
