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
            // Hanya menghapus kolom status_teknisi
            $table->dropColumn('status_teknisi');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            // Untuk mengembalikan jika dirollback
            $table->enum('status_teknisi', ['menunggu', 'diproses', 'selesai'])
                  ->default('menunggu')
                  ->after('status_sarpras');
        });
    }  
        
    
};
