<?php

namespace Database\Factories;

use App\Models\Kriteria;
use Illuminate\Database\Eloquent\Factories\Factory;

class KriteriaFactory extends Factory
{
    protected $model = Kriteria::class;

    public function definition()
    {
        return [
            'nama_kriteria' => $this->faker->unique()->randomElement([
                'Frekuensi Penggunaan Fasilitas',
                'Dampak Terhadap Aktivitas Akademik',
                'Tingkat Resiko Keselamatan',
                'Tingkat Kerusakan',
                'Estimasi Waktu',
                'Banyaknya Laporan',
            ]),
            'tipe' => $this->faker->randomElement(['benefit', 'cost']),
            'bobot' => $this->faker->randomFloat(2, 10, 30),
        ];
    }
}
