<?php

namespace App\Http\Livewire;

use App\Models\LaporanKerusakan;

use Livewire\Component;

class DashboardSarpras extends Component
{
    public $laporan = [];
    public function mount()
    {
        // Ambil data dari database
        $this->laporan = LaporanKerusakan::with(['gedung', 'ruangan'])
        ->where('status_admin', 'verifikasi')
        ->get();
    }
    public function render()
    {
        return view('livewire.dashboard-sarpras');
    }
    public function show($id)
    {
        LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])->findOrFail($id);
        return LihatDetailSarpras::class;
    }
}
