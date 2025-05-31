<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hasil_topsis', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai', 8, 4); // Nilai hasil TOPSIS
            $table->unsignedBigInteger('alternatif_id'); // Relasi ke alternatif
            
            $table->foreign('alternatif_id')
                  ->references('id')
                  ->on('alternatif')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_topsis');
    }
};