<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubKriteria;
use App\Models\Kriteria;


class SubKriteriaSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua kriteria untuk mapping
        $kriterias = Kriteria::all();

        $subKriterias = [
            'Frekuensi Penggunaan Fasilitas' => [
                ['nama_subkriteria' => 'Jarang (<1x/minggu)', 'nilai' => 1.00],
                ['nama_subkriteria' => 'Periodik (2-3x/minggu)', 'nilai' => 2.00],
                ['nama_subkriteria' => 'Rutin (hampir setiap hari)', 'nilai' => 3.00],
            ],
            'Dampak Terhadap Aktivitas Akademik' => [
                ['nama_subkriteria' => 'Minimal (tidak mengganggu)', 'nilai' => 1.00],
                ['nama_subkriteria' => 'Parsial (mengganggu sebagian)', 'nilai' => 2.00],
                ['nama_subkriteria' => 'Signifikan (menghentikan aktivitas)', 'nilai' => 3.00],
            ],
            'Tingkat Resiko Keselamatan' => [
                ['nama_subkriteria' => 'Aman', 'nilai' => 1.00],
                ['nama_subkriteria' => 'Waspada', 'nilai' => 2.00],
                ['nama_subkriteria' => 'Bahaya', 'nilai' => 3.00],
            ],
            'Estimasi Waktu' => [
                ['nama_subkriteria' => 'Cepat (≤1 hari)', 'nilai' => 1.00],
                ['nama_subkriteria' => 'Sedang (2-3 hari)', 'nilai' => 2.00],
                ['nama_subkriteria' => 'Lama (≥4 hari)', 'nilai' => 3.00],
            ],
            'Tingkat Kerusakan' => [
                ['nama_subkriteria' => 'Ringan (minor)', 'nilai' => 1.00],
                ['nama_subkriteria' => 'Sedang (perlu perbaikan)', 'nilai' => 2.00],
                ['nama_subkriteria' => 'Berat (ganti total)', 'nilai' => 3.00],
            ]
        ];

        foreach ($subKriterias as $kriteriaName => $subs) {
            $kriteria = $kriterias->firstWhere('nama_kriteria', $kriteriaName);
            
            if ($kriteria) {
                foreach ($subs as $sub) {
                    SubKriteria::updateOrCreate(
                        [
                            'kriterias_id' => $kriteria->id,
                            'nama_subkriteria' => $sub['nama_subkriteria']
                        ],
                        [
                            'nilai' => $sub['nilai']
                        ]
                    );
                }
            }
        }
    }
}