<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PerhitunganSpk extends Component
{
    public $data = [];

    public function mount()
    {
        $this->data = [
            ['alternatif' => 'A1', 'c1' => 80, 'c2' => 75, 'status' => 'Layak'],
            ['alternatif' => 'A2', 'c1' => 60, 'c2' => 65, 'status' => 'Dipertimbangkan'],
            ['alternatif' => 'A3', 'c1' => 40, 'c2' => 50, 'status' => 'Tidak Layak'],
        ];
    }

    public function render()
    {
        return view('livewire.perhitungan-spk', [
            'data' => $this->data,
        ]);
    }
}
