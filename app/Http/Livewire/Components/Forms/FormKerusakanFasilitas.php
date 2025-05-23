<?php

namespace App\Http\Livewire\Components\Forms;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Fasilitas;
use App\Models\Gedung;


class FormKerusakanFasilitas extends Component
{
    use WithFileUploads;
    public $nama_pelapor;
    public $identifier;
    public $nama_gedung = '';
    public $nama_fasilitas = '';
    public $ruang = '';
    public $daftarRuangan = [];
    public $daftarFasilitas = [];
    public $daftarGedung = [];
    public $deskripsi;
    public $kategori;
    public $foto;

    public function mount()
    {
        $this->nama_pelapor = Auth::user()->name; // atau 'nama' sesuai field user
        $this->identifier = Auth::user()->identifier;
    }

    protected $rules = [
        'nama_fasilitas' => 'required|exists:nama_fasilitas,id',
        'nama_gedung' => 'required|exists:gedung,id',
        'ruang' => 'required|exists:ruang,id',
        'deskripsi' => 'required|string|min:10',
        'kategori' => 'required|in:komputer,kursi,lampu,jaringan internet',
        'foto' => 'required|image|max:2048', // maksimal 2MB
    ];

    public function submit()
    {
        $this->validate();

        // Simpan laporan (dummy dulu, sesuaikan nanti)
        $path = $this->foto->store('public/bukti');

        // Simulasi penyimpanan
        session()->flash('success', 'Laporan kerusakan berhasil dikirim.');

        // Reset input
        $this->reset(['deskripsi', 'kategori', 'foto', 'ruang', 'nama_gedung', 'nama_fasilitas', 'nama_pelapor', 'identifier']);
    }

    public function render()
    {
        $this->daftarFasilitas = Fasilitas::all();
        $this->daftarGedung = Gedung::all();
        $this->daftarRuangan = Fasilitas::all();
        return view('livewire.components.forms.form-kerusakan-fasilitas',[
            'fasilitasList' => $this->daftarFasilitas,
            'gedungList' => $this->daftarGedung,
            'ruangList' => $this->daftarRuangan
        ]);
    }
}
