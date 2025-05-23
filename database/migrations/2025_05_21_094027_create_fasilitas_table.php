<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_fasilitas')->unique(); // Tambahan kode fasilitas
            $table->string('nama_fasilitas');
            $table->integer('jumlah')->default(1);
            $table->foreignId('ruangan_id')->constrained('ruangan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fasilitas');
    }
};