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
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->enum('status_teknisi', ['pending', 'sedang dikerjakan', 'selesai'])->default('pending')->after('status_sarpras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropColumn('status_teknisi');
        });
    }
};
