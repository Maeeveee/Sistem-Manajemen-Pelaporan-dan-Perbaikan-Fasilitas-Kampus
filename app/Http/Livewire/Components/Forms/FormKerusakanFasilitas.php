<?php

namespace App\Http\Livewire\Components\Forms;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\LaporanKerusakan;
use App\Models\Periode;

class FormKerusakanFasilitas extends Component
{
    use WithFileUploads;

    public $nama_pelapor;
    public $identifier;

    public $gedung_id, $ruangan_id, $fasilitas_id, $deskripsi, $foto;
    public $lantai = null;

    public $gedungList = [];
    public $lantaiList = [];
    public $ruanganList = [];
    public $fasilitasList = [];

    protected $rules = [
        'gedung_id'     => 'required|exists:gedung,id',
        'lantai'        => 'required|integer|min:1',
        'ruangan_id'    => 'required|exists:ruangan,id',
        'fasilitas_id'  => 'required|exists:fasilitas,id',
        'deskripsi'     => 'required|string|min:10',
        'foto'          => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->nama_pelapor = $user->name;
        $this->identifier = $user->identifier;

        $this->loadGedung();
    }

    public function updatedGedungId($value)
    {
        $this->reset(['lantai', 'ruangan_id', 'fasilitas_id']);
        $this->reset(['lantaiList', 'ruanganList', 'fasilitasList']);

        if ($value) {
            $this->lantaiList = Ruangan::where('gedung_id', $value)
                ->distinct()
                ->orderBy('lantai')
                ->pluck('lantai')
                ->toArray();
        }
    }

    public function updatedLantai($value)
    {
        $this->reset(['ruangan_id', 'fasilitas_id']);
        $this->reset(['ruanganList', 'fasilitasList']);

        if ($this->gedung_id && $value) {
            $this->ruanganList = Ruangan::where('gedung_id', $this->gedung_id)
                ->where('lantai', (int) $value)
                ->orderBy('nama_ruangan')
                ->get();
        }
    }

    public function updatedRuanganId($value)
    {
        $this->reset('fasilitas_id');
        $this->fasilitasList = [];

        if ($value) {
            $this->fasilitasList = Fasilitas::where('ruangan_id', $value)
                ->orderBy('nama_fasilitas')
                ->get();
        }
    }

    public function submit()
    {
        $this->validate();

        try {

            $periodes = Periode::where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->first();
            if (!$periodes) {
                session()->flash('error', 'Tidak ada periode aktif. Silakan hubungi admin.');
                return;
            }

            $path = $this->foto->store('laporan-kerusakan', 'public');

            LaporanKerusakan::create([
                'nama_pelapor'      => $this->nama_pelapor,
                'identifier'        => $this->identifier,
                'gedung_id'         => $this->gedung_id,
                'ruangan_id'        => $this->ruangan_id,
                'lantai'            => $this->lantai,
                'fasilitas_id'      => $this->fasilitas_id,
                'deskripsi'         => $this->deskripsi,
                'foto'              => $path,
                'status'            => 'dilaporkan',
                'periode_id'        => $periodes->id,
            ]);

            $this->resetForm();
            session()->flash('success', 'Laporan kerusakan berhasil dikirim.');
            $this->emit('laporanBerhasil');

        } catch (\Exception $e) {
            $this->addError('foto', 'Gagal memproses gambar: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->reset([
            'gedung_id', 'lantai', 'ruangan_id', 'fasilitas_id',
            'deskripsi', 'foto'
        ]);
        $this->resetErrorBag();
        $this->loadGedung();
    }

    private function loadGedung()
    {
        $this->gedungList = Gedung::orderBy('nama_gedung')->get();
    }

    public function render()
    {
        return view('livewire.components.forms.form-kerusakan-fasilitas');
    }
}