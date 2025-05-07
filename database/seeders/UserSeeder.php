<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin', // Role Dosen
        ]);

        User::create([
            'name' => 'Mahasiswa 1',
            'email' => 'mahasiswa1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa', // Role Mahasiswa
        ]);
    }
}
