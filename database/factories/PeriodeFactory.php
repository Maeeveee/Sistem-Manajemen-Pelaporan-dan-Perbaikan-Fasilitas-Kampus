<?php

namespace Database\Factories;

use App\Models\Periode;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeriodeFactory extends Factory
{
    protected $model = Periode::class;

    public function definition()
    {
        return [
            'nama_periode' => $this->faker->year . ' - ' . $this->faker->monthName,
            'tanggal_mulai' => $this->faker->date(),
            'tanggal_selesai' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
            'status' => $this->faker->randomElement(['aktif', 'non-aktif']),
            'keterangan' => $this->faker->sentence,
        ];
    }
}
