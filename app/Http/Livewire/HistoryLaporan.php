<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;

class HistoryLaporan extends Component
{
    public $laporans;
    public $search = '';
    public $statusFilter = 'all';
    public $selectedLaporan;
    public $showModal = false;

    public function mount()
    {
        $this->loadLaporans();
    }
    
    public function loadLaporans()
    {
        $query = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])
            ->where('identifier', Auth::user()->identifier)
            ->orderBy('created_at', 'desc');
            
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('gedung', function($q) use ($searchTerm) {
                    $q->where('nama_gedung', 'like', $searchTerm);
                })
                ->orWhereHas('ruangan', function($q) use ($searchTerm) {
                    $q->where('nama_ruangan', 'like', $searchTerm);
                })
                ->orWhereHas('fasilitas', function($q) use ($searchTerm) {
                    $q->where('nama_fasilitas', 'like', $searchTerm);
                })
                ->orWhere('deskripsi', 'like', $searchTerm);
            });
        }
        
        if ($this->statusFilter !== 'all') {
            $query->where('status_admin', $this->statusFilter);
        }
        
        $this->laporans = $query->get();
    }
    
    public function updatedSearch()
    {
        $this->loadLaporans();
    }
    
    public function updatedStatusFilter()
    {
        $this->loadLaporans();
    }

    public function showDetail($id)
    {
        $this->selectedLaporan = LaporanKerusakan::with([
            'gedung', 
            'ruangan', 
            'fasilitas',
            'frekuensiPenggunaan',
            'tingkatKerusakan',
            'dampakAkademik',        
            'resikoKeselamatan'      
        ])->findOrFail($id);

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedLaporan = null;
    }

    public function render()
    {
        return view('livewire.history-laporan');
    }
}