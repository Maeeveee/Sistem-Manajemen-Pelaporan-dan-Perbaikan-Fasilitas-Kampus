<?php

namespace Database\Factories;

use App\Models\Fasilitas;
use Illuminate\Database\Eloquent\Factories\Factory;

class FasilitasFactory extends Factory
{
    protected $model = Fasilitas::class;

    public function definition()
    {
        return [
            'nama_fasilitas' => $this->faker->randomElement([
                'Proyektor',
                'AC',
                'Kursi',
                'Meja',
                'Whiteboard',
                'Komputer',
                'Printer',
                'Lampu',
            ]),
            'keterangan' => $this->faker->sentence,
        ];
    }
}
