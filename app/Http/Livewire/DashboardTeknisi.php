<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardTeknisi extends Component
{
    use WithFileUploads;
    public $laporanDiproses = [];
    public $laporanSelesai = [];
    public $showModal = false;
    public $selectedLaporan = null;
    public $statusSelected = 'diproses';
    public $catatanTeknisi = '';
    public $search = '';
    public $statusFilter = 'all';
    public $fotoPerbaikan;
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
        // Validasi input
        $this->validate([
            'statusSelected' => 'required|in:diproses,selesai,ditunda',
            'catatanTeknisi' => 'nullable|string|max:500',
            'fotoPerbaikan' => 'nullable|image|max:2048' // 2MB max
        ]);

        try {
            DB::beginTransaction();

            // Data yang akan diupdate
            $updateData = [
                'status_perbaikan' => $this->statusSelected,
                'catatan_teknisi' => $this->catatanTeknisi,
                'teknisi_id' => auth()->id() // Pastikan teknisi_id terisi
            ];

            // Handle file upload
            if ($this->fotoPerbaikan) {
                // Hapus foto lama jika ada
                if ($this->selectedLaporan->foto_perbaikan) {
                    Storage::delete('public/laporan-perbaikan/'.$this->selectedLaporan->foto_perbaikan);
                }
                
                $filename = 'perbaikan_'.$this->selectedLaporan->id.'_'.time().'.'.$this->fotoPerbaikan->extension();
                $path = $this->fotoPerbaikan->storeAs('public/laporan-perbaikan', $filename);
                $updateData['foto_perbaikan'] = $filename;
            }

            // Update data
            $updated = $this->selectedLaporan->update($updateData);

            if (!$updated) {
                throw new \Exception('Gagal menyimpan perubahan ke database');
            }

            DB::commit();

            // Refresh data dan tampilkan notifikasi
            $this->loadData();
            $this->closeModal();
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Status perbaikan berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating laporan: '.$e->getMessage(), [
                'laporan_id' => $this->selectedLaporan->id ?? null,
                'user_id' => auth()->id()
            ]);
            
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Gagal memperbarui status: '.$e->getMessage()
            ]);
        }
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
