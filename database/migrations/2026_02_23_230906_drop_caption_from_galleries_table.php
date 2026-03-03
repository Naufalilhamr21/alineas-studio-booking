<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Menghapus kolom caption
            $table->dropColumn('caption');
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Mengembalikan kolom jika kita melakukan rollback
            $table->string('caption')->nullable();
        });
    }
};