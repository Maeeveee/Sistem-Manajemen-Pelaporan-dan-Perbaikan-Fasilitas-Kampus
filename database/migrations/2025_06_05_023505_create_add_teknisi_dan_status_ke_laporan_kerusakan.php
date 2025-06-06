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
            // Hanya tambahkan kolom untuk status perbaikan dan catatan
            if (!Schema::hasColumn('laporan_kerusakan', 'status_perbaikan')) {
                $table->enum('status_perbaikan', ['menunggu', 'diproses', 'selesai', 'ditolak'])
                      ->default('menunggu')
                      ->after('status_sarpras');
            }
            
            if (!Schema::hasColumn('laporan_kerusakan', 'catatan_teknisi')) {
                $table->text('catatan_teknisi')->nullable()->after('status_perbaikan');
            }
            
            if (!Schema::hasColumn('laporan_kerusakan', 'tingkat_prioritas')) {
                $table->decimal('tingkat_prioritas', 8, 4)->nullable()->after('catatan_teknisi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $columnsToDrop = ['status_perbaikan', 'catatan_teknisi', 'tingkat_prioritas'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('laporan_kerusakan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }  
};