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
        Schema::create('matriks_keputusan', function (Blueprint $table) {
            $table->id();
            $table->decimal('nilai', 8, 2); // Angka dengan 8 digit total dan 2 digit desimal
            $table->unsignedBigInteger('kriteria_id');
            
            // Foreign key
            $table->foreign('kriteria_id')
                  ->references('id')
                  ->on('kriterias')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('matriks_keputusan');
    }
};