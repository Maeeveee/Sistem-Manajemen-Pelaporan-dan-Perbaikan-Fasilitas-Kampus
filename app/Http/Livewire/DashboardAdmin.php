<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;

class DashboardAdmin extends Component
{
   public $laporan = [];

    public function mount()
    {
        // Ambil data dari database
        $this->laporan = LaporanKerusakan::with(['gedung', 'ruangan'])->get();
    }
    public function render()
    {
        return view('livewire.dashboard-admin');
    }

    public function show($id)
    {
        $laporan = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])->findOrFail($id);
        return LihatDetailAdmin::class;
    }
}
