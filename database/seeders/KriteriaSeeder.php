<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        $fixedKriterias = [
            [
                'nama_kriteria' => 'Frekuensi Penggunaan Fasilitas',
                'bobot' => 25.00,
            ],
            [
                'nama_kriteria' => 'Dampak Terhadap Aktivitas Akademik',
                'bobot' => 20.00,
            ],
            [
                'nama_kriteria' => 'Tingkat Resiko Keselamatan',
                'bobot' => 15.00,
            ],
            [
                'nama_kriteria' => 'Estimasi Waktu',
                'bobot' => 20.00,
            ],
            [
                'nama_kriteria' => 'Tingkat Kerusakan',
                'bobot' => 20.00,
            ]
        ];

        foreach ($fixedKriterias as $kriteria) {
            Kriteria::updateOrCreate(
                ['nama_kriteria' => $kriteria['nama_kriteria']],
                $kriteria
            );
        }
    }
}