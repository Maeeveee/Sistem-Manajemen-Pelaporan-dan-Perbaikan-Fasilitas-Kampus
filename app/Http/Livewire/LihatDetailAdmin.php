<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;

class LihatDetailAdmin extends Component
{
    public $laporan;
    // public $status_admin;
    // public $komentar_admin;

    public function mount($id)
    {
        $this->laporan = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])->findOrFail($id);
    }
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status_admin' => 'required|in:verifikasi,reject',
        'komentar_admin' => 'nullable|string',
    ]);

    $laporan = LaporanKerusakan::findOrFail($id);
    $laporan->status_admin = $request->status_admin;
    $laporan->komentar_admin = $request->komentar_admin;
    $laporan->save();

    return redirect()->back()->with('success', 'Status berhasil diperbarui.');
}



    public function render()
    {
        return view('livewire.lihat-detail-admin');
    }
}
