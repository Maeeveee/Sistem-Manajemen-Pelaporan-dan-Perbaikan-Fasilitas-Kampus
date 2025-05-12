<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Fasilitas;

class ManajemenFasilitas extends Component
{
    public $nama_fasilitas, $kategori, $status;
    public $fasilitasId;
    public $isEdit = false;

    protected $rules = [
        'nama_fasilitas' => 'required|string|max:255',
        'kategori' => 'required|string|max:100',
        'status' => 'required|string|max:50',
    ];

    public function render()
    {
        return view('livewire.manajemen-fasilitas', [
            'fasilitas' => Fasilitas::latest()->get()
        ]);
    }

    public function resetInput()
    {
        $this->nama_fasilitas = '';
        $this->kategori = '';
        $this->status = '';
        $this->fasilitasId = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();

        Fasilitas::create([
            'nama_fasilitas' => $this->nama_fasilitas,
            'kategori' => $this->kategori,
            'status' => $this->status,
        ]);

        $this->resetInput();
        session()->flash('message', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $this->fasilitasId = $fasilitas->id;
        $this->nama_fasilitas = $fasilitas->nama_fasilitas;
        $this->kategori = $fasilitas->kategori;
        $this->status = $fasilitas->status;
        $this->isEdit = true;
    }
    

    public function update()
    {
        $this->validate();

        if ($this->fasilitasId) {
            $fasilitas = Fasilitas::find($this->fasilitasId);
            $fasilitas->update([
                'nama_fasilitas' => $this->nama_fasilitas,
                'kategori' => $this->kategori,
                'status' => $this->status,
            ]);

            session()->flash('message', 'Fasilitas berhasil diperbarui.');
        }

        $this->resetInput();
    }

    public function delete($id)
    {
        Fasilitas::findOrFail($id)->delete();
        session()->flash('message', 'Fasilitas berhasil dihapus.');
    }
    
}
