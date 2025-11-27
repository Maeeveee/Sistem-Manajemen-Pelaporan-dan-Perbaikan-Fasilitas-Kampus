<?php

namespace Database\Factories;

use App\Models\Ruangan;
use App\Models\Gedung;
use Illuminate\Database\Eloquent\Factories\Factory;

class RuanganFactory extends Factory
{
    protected $model = Ruangan::class;

    public function definition()
    {
        return [
            'nama_ruangan' => $this->faker->randomElement(['Lab', 'Kelas', 'Ruang']) . ' ' . $this->faker->numberBetween(101, 501),
            'gedung_id' => Gedung::factory(),
            'keterangan' => $this->faker->sentence,
        ];
    }
}
