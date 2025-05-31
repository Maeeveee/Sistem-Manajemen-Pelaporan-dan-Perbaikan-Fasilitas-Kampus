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
        Schema::create('solusi_ideal_negatif', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai', 10, 6); // Decimal dengan presisi tinggi
            $table->unsignedBigInteger('kriteria_id'); // Tambahkan kriteria_id
            $table->unsignedBigInteger('alternatif_id')->nullable(); // Bisa nullable jika ini solusi ideal umum
            
            // Foreign key untuk alternatif
            $table->foreign('alternatif_id')
                  ->references('id')
                  ->on('alternatif')
                  ->onDelete('cascade');
                  
            // Foreign key untuk kriteria
            $table->foreign('kriteria_id')
                  ->references('id')
                  ->on('kriterias')
                  ->onDelete('cascade');
            
            $table->timestamps();
            
            // Composite unique untuk alternatif_id dan kriteria_id
            $table->unique(['alternatif_id', 'kriteria_id'], 'sin_alt_kriteria_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('solusi_ideal_negatif');
    }
};