<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use App\Models\Periode;

class DashboardSarpras extends Component
{
    public $laporan = [];
    public $periode_id;
    public $periodes = [];

    public function mount()
    {
        $this->periodes = Periode::orderBy('created_at', 'desc')->get();
        $this->loadLaporan();
    }

    public function updatedPeriodeId()
    {
        $this->loadLaporan();
    }

    public function loadLaporan()
    {
        $query = LaporanKerusakan::with(['gedung', 'ruangan'])
            ->where('status_admin', 'verifikasi');
        if ($this->periode_id) {
            $query->where('periode_id', $this->periode_id);
        }
        $this->laporan = $query->get();
    }

    public function show($id)
    {
        $laporan = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])->findOrFail($id);
        $this->emit('showDetail', $id);
    }

    public function render()
    {
        return view('livewire.dashboard-sarpras', [
            'periodes' => $this->periodes,
        ]);
    }
}