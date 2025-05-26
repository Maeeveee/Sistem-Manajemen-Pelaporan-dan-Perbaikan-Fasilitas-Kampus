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
                'nama_kriteria' => 'Biaya Perbaikan',
                'jenis' => 'benefit',
                'bobot' => 25.00,
                'nilai_rendah' => 1.00,
                'nilai_sedang' => 2.00,
                'nilai_tinggi' => 3.00,
            ],
            [
                'nama_kriteria' => 'Dampak',
                'jenis' => 'cost',
                'bobot' => 20.00,
                'nilai_rendah' => 1.00,
                'nilai_sedang' => 2.00,
                'nilai_tinggi' => 3.00,
            ],
            [
                'nama_kriteria' => 'Tenaga yang Dibutuhkan',
                'jenis' => 'benefit',
                'bobot' => 15.00,
                'nilai_rendah' => 1.00,
                'nilai_sedang' => 2.00,
                'nilai_tinggi' => 3.00,
            ],
            [
                'nama_kriteria' => 'Waktu',
                'jenis' => 'cost',
                'bobot' => 20.00,
                'nilai_rendah' => 1.00,
                'nilai_sedang' => 2.00,
                'nilai_tinggi' => 3.00,
            ],
            [
                'nama_kriteria' => 'Tingkat Kerusakan',
                'jenis' => 'benefit',
                'bobot' => 20.00,
                'nilai_rendah' => 1.00,
                'nilai_sedang' => 2.00,
                'nilai_tinggi' => 3.00,
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