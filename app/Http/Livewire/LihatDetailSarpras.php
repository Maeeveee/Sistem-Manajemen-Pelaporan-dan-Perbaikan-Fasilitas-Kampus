<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;


class LihatDetailSarpras extends Component
{
    public $laporan;
    // public $status_admin;
    // public $komentar_admin;

    public function mount($id)
    {
        $this->laporan = LaporanKerusakan::with(['gedung', 'ruangan', 'fasilitas'])->findOrFail($id);
    }

    public function updateEstimasi(Request $request, $id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        if ($laporan->sub_kriteria_id !== null) {
            return back()->with('error', 'Kriteria waktu sudah ditentukan dan tidak bisa diubah.');
        }

        $laporan->frekuensi_penggunaan_fasilitas = $request->input('frekuensi_penggunaan_fasilitas');
        $laporan->tingkat_kerusakan = $request->input('tingkat_kerusakan');
        $laporan->dampak_terhadap_aktivitas_akademik = $request->input('dampak_terhadap_aktivitas_akademik');
        $laporan->tingkat_resiko_keselamatan = $request->input('tingkat_resiko_keselamatan');
        $laporan->sub_kriteria_id = $request->input('sub_kriteria_id');
        $laporan->save();

        return redirect()->back()->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.lihat-detail-sarpras');
    }
}
