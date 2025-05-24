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
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->id();

            $table->string('nama_pelapor')->nullable();
            $table->string('identifier')->nullable();
            $table->foreignId('gedung_id')->constrained('gedung')->onDelete('cascade');
            $table->foreignId('ruangan_id')->constrained('ruangan')->onDelete('cascade');
            $table->foreignId('lantai')->constrained('ruangan')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');

            $table->text('deskripsi');
            $table->text('foto');
            $table->enum('status', ['dilaporkan', 'diproses', 'selesai', 'ditolak'])->default('dilaporkan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
};