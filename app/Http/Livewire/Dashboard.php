<?php

namespace App\Http\Livewire;

use App\Models\LaporanKerusakan;
use Livewire\Component;

class Dashboard extends Component
{
    public $laporanPerBulan = [];
    public $laporanSelesai = 0;
    public $laporanDitolak = 0;
    public $laporanDiproses = 0;

    public function render()
    {
        return view('dashboard');
    }

    public function mount()
    {
        $this->laporanPerBulan = LaporanKerusakan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $this->laporanSelesai = LaporanKerusakan::where('status', 'selesai')->count();
        $this->laporanDitolak = LaporanKerusakan::where('status', 'ditolak')->count();
        $this->laporanDiproses = LaporanKerusakan::where('status', 'diproses')->count();
    }
}
