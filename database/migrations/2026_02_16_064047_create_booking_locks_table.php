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
        Schema::create('booking_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();

            $table->date('date');
            $table->string('time');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique(['date', 'time'], 'unique_global_slot_lock');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_locks');
    }
};
