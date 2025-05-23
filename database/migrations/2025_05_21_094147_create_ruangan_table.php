<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruangan');
            $table->integer('lantai');
            $table->foreignId('gedung_id')->constrained('gedung')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruangan');
    }
};