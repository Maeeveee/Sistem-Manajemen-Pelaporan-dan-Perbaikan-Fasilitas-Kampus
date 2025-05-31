<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('matriks_normalisasi_keputusan', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai', 10, 6); // Decimal dengan presisi tinggi untuk perhitungan normalisasi
            $table->unsignedBigInteger('alternatif_id');
            $table->unsignedBigInteger('kriteria_id');
            
            // Foreign keys
            $table->foreign('alternatif_id')
                  ->references('id')
                  ->on('alternatif')
                  ->onDelete('cascade');
                  
            $table->foreign('kriteria_id')
                  ->references('id')
                  ->on('kriterias')
                  ->onDelete('cascade');
            
            $table->timestamps();
            
            // Composite unique index
            $table->unique(['alternatif_id', 'kriteria_id'], 'matriks_norm_alt_kriteria_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('matriks_normalisasi_keputusan');
    }
};