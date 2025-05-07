<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'description' => 'Administrator sistem', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'User', 'description' => 'Pengguna umum (Mahasiswa/Dosen/Tendik)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Sarana Prasarana', 'description' => 'Staff sarana prasarana', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Teknisi', 'description' => 'Staff teknisi', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}