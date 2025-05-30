<?php

namespace App\Http\Livewire\Components\Forms;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\LaporanKerusakan;
use App\Models\SubKriteria;

class FormKerusakanFasilitas extends Component
{
    use WithFileUploads;

    public $subKriterias;

    public $frekuensi_penggunaan_fasilitas;
    public $tingkat_kerusakan;
    public $dampak_terhadap_aktivitas_akademik;
    public $tingkat_resiko_keselamatan;

    public $nama_pelapor;
    public $identifier;

    public $gedung_id, $ruangan_id, $fasilitas_id, $deskripsi, $foto;
    public $lantai = null;

    public $gedungList = [];
    public $lantaiList = [];
    public $ruanganList = [];
    public $fasilitasList = [];

    protected $rules = [
        'gedung_id'                             => 'required|exists:gedung,id',
        'lantai'                                => 'required|integer|min:1',
        'ruangan_id'                            => 'required|exists:ruangan,id',
        'fasilitas_id'                          => 'required|exists:fasilitas,id',
        'frekuensi_penggunaan_fasilitas'       => 'required|exists:sub_kriterias,id',
        'tingkat_kerusakan'                    => 'required|exists:sub_kriterias,id',
        'dampak_terhadap_aktivitas_akademik'   => 'required|exists:sub_kriterias,id',
        'tingkat_resiko_keselamatan'           => 'required|exists:sub_kriterias,id',
        'deskripsi'                             => 'required|string|min:10',
        'foto'                                  => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->nama_pelapor = $user->name;
        $this->identifier = $user->identifier;

        $this->loadGedung();
        $this->subKriterias = SubKriteria::with('kriteria')->get();
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
            $path = $this->foto->store('laporan-kerusakan', 'public');

            LaporanKerusakan::create([
                'nama_pelapor'                          => $this->nama_pelapor,
                'identifier'                            => $this->identifier,
                'gedung_id'                             => $this->gedung_id,
                'ruangan_id'                            => $this->ruangan_id,
                'lantai'                                => $this->lantai,
                'fasilitas_id'                          => $this->fasilitas_id,
                'frekuensi_penggunaan_fasilitas'        => $this->frekuensi_penggunaan_fasilitas,
                'tingkat_kerusakan'                     => $this->tingkat_kerusakan,
                'dampak_terhadap_aktivitas_akademik'    => $this->dampak_terhadap_aktivitas_akademik,
                'tingkat_resiko_keselamatan'            => $this->tingkat_resiko_keselamatan,
                'deskripsi'                             => $this->deskripsi,
                'foto'                                  => $path,
                'status'                                => 'dilaporkan',
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
            'frekuensi_penggunaan_fasilitas', 'tingkat_kerusakan',
            'dampak_terhadap_aktivitas_akademik', 'tingkat_resiko_keselamatan',
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