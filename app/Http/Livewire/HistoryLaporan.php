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
            
        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('gedung', function($q) {
                    $q->where('nama_gedung', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('ruangan', function($q) {
                    $q->where('nama_ruangan', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('fasilitas', function($q) {
                    $q->where('nama_fasilitas', 'like', '%'.$this->search.'%');
                })
                ->orWhere('deskripsi', 'like', '%'.$this->search.'%');
            });
        }
        
        // Apply status filter
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
        $this->selectedLaporan = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])->findOrFail($id);
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