<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use App\Models\HasilTopsis;


class DashboardTeknisi extends Component
{
    public $laporanDiproses = [];
    public $laporanSelesai = [];
    public $selectedLaporan;
    public $statusSelected;
    public $catatanTeknisi;

    protected $rules = [
        'statusSelected' => 'required|in:diproses,selesai,ditunda',
        'catatanTeknisi' => 'nullable|string|max:500'
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        
        $this->laporanDiproses = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas', 'hasilTopsis'])
            ->where('teknisi_id', $user->id)
            ->where('status_perbaikan', 'diproses')
            ->orderByDesc(
                HasilTopsis::select('nilai')
                    ->whereColumn('hasil_topsis.alternatif_id', 'laporan_kerusakan.id')
            )
            ->get();

        $this->laporanSelesai = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])
            ->where('teknisi_id', $user->id)
            ->whereIn('status_perbaikan', ['selesai', 'ditunda'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function selectLaporan($laporanId)
    {
        $this->selectedLaporan = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas', 'hasilTopsis'])
            ->find($laporanId);
            
        $this->statusSelected = $this->selectedLaporan->status_perbaikan;
        $this->catatanTeknisi = $this->selectedLaporan->catatan_teknisi;
    }

    public function updateStatus()
    {
        $this->validate();

        $this->selectedLaporan->update([
            'status_perbaikan' => $this->statusSelected,
            'catatan_teknisi' => $this->catatanTeknisi,
            'updated_at' => now()
        ]);

        session()->flash('message', 'Status perbaikan berhasil diperbarui');
        $this->loadData();
        $this->reset(['selectedLaporan', 'statusSelected', 'catatanTeknisi']);
    }
    public function render()
    {
        return view('livewire.dashboard-teknisi');
    }
}
