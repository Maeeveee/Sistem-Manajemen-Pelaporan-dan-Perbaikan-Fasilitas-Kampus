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
        Schema::create('solusi_ideal_positif', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai', 12, 8); // Presisi sangat tinggi untuk perhitungan SPK
            $table->unsignedBigInteger('kriteria_id'); // Wajib untuk relasi ke kriteria
            $table->unsignedBigInteger('alternatif_id')->nullable(); // Nullable untuk solusi umum
            
            // Foreign keys
            $table->foreign('kriteria_id')
                ->references('id')
                ->on('kriterias')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('alternatif_id')
                ->references('id')
                ->on('alternatif')
                ->onDelete('set null');
            
            // Optimasi query
            $table->index('kriteria_id');
            $table->index('alternatif_id');
            
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['kriteria_id', 'alternatif_id'], 'sip_kriteria_alternatif_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('solusi_ideal_positif');
    }
};