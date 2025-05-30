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
            $table->unsignedTinyInteger('lantai'); // lantai cukup integer biasa
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');

            // Tambahan relasi ke sub_kriteria
            $table->foreignId('frekuensi_penggunaan_fasilitas')->nullable()->constrained('sub_kriteria')->onDelete('set null');
            $table->foreignId('tingkat_kerusakan')->nullable()->constrained('sub_kriteria')->onDelete('set null');
            $table->foreignId('dampak_terhadap_aktivitas_akademik')->nullable()->constrained('sub_kriteria')->onDelete('set null');
            $table->foreignId('tingkat_resiko_keselamatan')->nullable()->constrained('sub_kriteria')->onDelete('set null');

            $table->text('deskripsi');
            $table->text('foto')->nullable();
            $table->enum('status', ['dilaporkan', 'diproses', 'selesai', 'ditolak'])->default('dilaporkan');

            $table->text('komentar_admin')->nullable();

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
