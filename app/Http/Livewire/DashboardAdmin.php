<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use App\Models\Periode;

class DashboardAdmin extends Component
{
    public $laporan = [];
    public $periodes = [];
    public $periode_id = '';
    public $statusFilter = 'all';
    public $search = '';

    public function mount()
    {
        $this->loadPeriode();
        $this->loadLaporan();
    }

    public function loadPeriode()
    {
        $this->periodes = Periode::orderBy('created_at', 'desc')->get();
    }

    public function loadLaporan()
    {
        $query = LaporanKerusakan::with(['gedung', 'ruangan']);

        if ($this->periode_id) {
            $query->where('periode_id', $this->periode_id);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status_admin', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_pelapor', 'like', '%' . $this->search . '%')
                  ->orWhereHas('gedung', function ($q) {
                      $q->where('nama_gedung', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('ruangan', function ($q) {
                      $q->where('nama_ruangan', 'like', '%' . $this->search . '%');
                  });
        
            });
        }

        $this->laporan = $query->get();
    }
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['periode_id', 'statusFilter', 'search'])) {
            $this->loadLaporan();
        }
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
