<?php

namespace App\Http\Livewire\Components\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;

class FormKerusakanFasilitas extends Component
{
    use WithFileUploads;

    public $deskripsi;
    public $kategori;
    public $foto;

    protected $rules = [
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
        $this->reset(['deskripsi', 'kategori', 'foto']);
    }

    public function render()
    {
        return view('livewire.components.forms.form-kerusakan-fasilitas');
    }
}

