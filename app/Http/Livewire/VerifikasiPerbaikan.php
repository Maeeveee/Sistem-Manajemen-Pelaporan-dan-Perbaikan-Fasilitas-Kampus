<?php

namespace App\Http\Livewire;

use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;
use Livewire\Component;

class VerifikasiPerbaikan extends Component
{


    public function render()
    {
        $laporan = LaporanKerusakan::where('status_teknisi', 'selesai')->with(['gedung', 'fasilitas'])->get();
        return view('livewire.verifikasi-perbaikan', compact('laporan'));
    }

    public function kirim($id)
    {
        $laporan = LaporanKerusakan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.'
            ]);
        }
        

        if ($laporan->status === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Laporan sudah selesai.'
            ]);
        }

        $laporan->status = 'selesai';
        $laporan->save();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim.'
        ]);
    }
}
