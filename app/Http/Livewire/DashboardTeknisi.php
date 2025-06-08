<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;

class DashboardTeknisi extends Component
{
    public $laporanDiproses = [];
    public $laporanSelesai = [];
    public $showModal = false;
    public $selectedLaporan = null;
    public $statusSelected = 'diproses';
    public $catatanTeknisi = '';
    public $search = '';
    public $statusFilter = 'all';
    public $perPage = 10;

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
        $user = Auth::user();
        
        $this->laporanDiproses = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas', 'hasilTopsis'])
            ->where('teknisi_id', $user->id)
            ->where('status_perbaikan', 'diproses')
            ->get()
            ->sortByDesc(function ($laporan) {
                return $laporan->hasilTopsis->nilai ?? 0;
            });

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
        
        if ($this->selectedLaporan) {
            $this->statusSelected = $this->selectedLaporan->status_perbaikan;
            $this->catatanTeknisi = $this->selectedLaporan->catatan_teknisi ?? '';
            $this->showModal = true;
            $this->dispatchBrowserEvent('showModal');
        }
    }

    public function updateStatus()
    {
        $this->validate();

        $this->selectedLaporan->update([
            'status_perbaikan' => $this->statusSelected,
            'catatan_teknisi' => $this->catatanTeknisi
        ]);
        
        $this->dispatchBrowserEvent('hideModal');
        $this->emitSelf('refreshData');   
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'diproses' => 'primary',
            'selesai' => 'success',
            'ditunda' => 'warning',
            'menunggu' => 'secondary',
            default => 'info'
        };
    }

    public function getStatusText($status)
    {
        return match($status) {
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditunda' => 'Ditunda',
            'menunggu' => 'Menunggu',
            default => $status
        };
    }
    public function closeModal()
{
    $this->reset(['showModal', 'selectedLaporan', 'statusSelected', 'catatanTeknisi']);
    $this->dispatchBrowserEvent('hideModal');
}
    public function render()
    {
        return view('livewire.dashboard-teknisi', [
            'laporanDiproses' => $this->laporanDiproses,
            'laporanSelesai' => $this->laporanSelesai,
        ]);
    }
}
