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

    // Tambahkan property untuk rating
    public $rating = null;
    public $ratingLaporanId = null;
    public $feedback = null;

    // Tampilkan form rating jika status sudah beres dan rating belum diisi
    public function showRatingForm($laporanId)
    {
        $laporan = LaporanKerusakan::findOrFail($laporanId);
        if ($laporan->status === 'selesai' && is_null($laporan->rating)) {
            $this->ratingLaporanId = $laporanId;
            $this->rating = null;
        }
    }

    // Simpan rating ke database
    public function submitRating()
{
    $this->validate([
        'rating' => 'required|integer|min:1|max:5',
        'feedback' => 'nullable|string|max:1000',
    ]);

    $laporan = LaporanKerusakan::findOrFail($this->selectedLaporan->id);

    // Hanya bisa rating jika status selesai dan rating belum ada
    if ($laporan->status === 'selesai' && is_null($laporan->rating)) {
        $laporan->rating = $this->rating;
        $laporan->feedback = $this->feedback;
        $laporan->save();

        // Reset property dan reload data
        $this->selectedLaporan = $laporan; // agar tampilan langsung update
        $this->rating = null;
        $this->feedback = null;
        $this->showModal = false;
        $this->loadLaporans();
        session()->flash('message', 'Rating dan komentar berhasil disimpan.');
    }
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