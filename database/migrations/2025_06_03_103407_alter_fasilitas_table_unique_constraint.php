<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFasilitasTableUniqueConstraint extends Migration
{
    public function up()
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            // Drop the existing unique index on kode_fasilitas
            $table->dropUnique(['kode_fasilitas']);
            
            // Add a composite unique index on kode_fasilitas and ruangan_id
            $table->unique(['kode_fasilitas', 'ruangan_id'], 'fasilitas_kode_ruangan_unique');
        });
    }

    public function down()
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            // Drop the composite unique index
            $table->dropUnique('fasilitas_kode_ruangan_unique');
            
            // Restore the original unique index on kode_fasilitas
            $table->unique('kode_fasilitas');
        });
    }
}