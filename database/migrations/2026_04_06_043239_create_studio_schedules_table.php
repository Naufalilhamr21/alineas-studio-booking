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
        Schema::create('studio_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->boolean('is_closed')->default(false); // True = Tutup, False = Buka dengan jam khusus
            $table->time('open_time')->nullable();  // Jam buka khusus (misal: 13:00)
            $table->time('close_time')->nullable(); // Jam tutup khusus (misal: 21:00)
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_schedules');
    }
};
