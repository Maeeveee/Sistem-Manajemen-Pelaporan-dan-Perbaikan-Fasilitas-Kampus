<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gedung', function (Blueprint $table) {
            $table->id();
            $table->string('kode_gedung')->unique(); // Tambahan kode gedung
            $table->string('nama_gedung', 100);
            $table->integer('jumlah_lantai')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gedung');
    }
};