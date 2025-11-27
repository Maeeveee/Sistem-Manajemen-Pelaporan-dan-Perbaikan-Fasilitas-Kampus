<?php

namespace Database\Factories;

use App\Models\Gedung;
use Illuminate\Database\Eloquent\Factories\Factory;

class GedungFactory extends Factory
{
    protected $model = Gedung::class;

    public function definition()
    {
        return [
            'nama_gedung' => 'Gedung ' . $this->faker->randomLetter . $this->faker->numberBetween(1, 10),
            'jumlah_lantai' => $this->faker->numberBetween(1, 5),
            'keterangan' => $this->faker->sentence,
        ];
    }
}
