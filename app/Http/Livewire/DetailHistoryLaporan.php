<?php

namespace App\Http\Livewire;

use App\Models\LaporanKerusakan;
use Livewire\Component;

class DetailHistoryLaporan extends Component
{
    public $laporan;

    public function mount($id)
    {
        $this->laporan = LaporanKerusakan::with([
            'gedung', 
            'ruangan', 
            'fasilitas'
        ])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.detail-history-laporan');
    }
}