<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    
        // Perbaiki insert data
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'description' => 'Administrator sistem', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'User', 'description' => 'Pengguna umum (Mahasiswa/Dosen/Tendik)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Sarana Prasarana', 'description' => 'Staff sarana prasarana', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Teknisi', 'description' => 'Staff teknisi', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};