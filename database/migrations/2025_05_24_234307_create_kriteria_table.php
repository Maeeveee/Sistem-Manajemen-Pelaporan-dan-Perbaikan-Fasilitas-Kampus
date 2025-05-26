<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria')->unique();
            $table->enum('jenis', ['benefit', 'cost']);
            $table->decimal('bobot', 5, 2)->comment('Dalam persentase');
            $table->decimal('nilai_rendah', 10, 2);
            $table->decimal('nilai_sedang', 10, 2);
            $table->decimal('nilai_tinggi', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
