<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class RiwayatPerbaikan extends Component
{

    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        $riwayatPerbaikan = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])
        ->where('status_perbaikan', 'selesai')
        ->where('teknisi_id', $user->id)
        ->where(function ($query) {
            $query->whereHas('fasilitas', function ($q) {
                $q->where('nama_fasilitas', 'like', '%' . $this->search . '%');
            })->orWhere('deskripsi', 'like', '%' . $this->search . '%');
        })
        ->latest('updated_at')
        ->paginate(10);

        

        return view('livewire.riwayat-perbaikan', compact('riwayatPerbaikan'));
    }
}
