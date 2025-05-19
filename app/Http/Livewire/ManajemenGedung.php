<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Gedung;

class ManajemenGedung extends Component
{
    use WithPagination;

    public $nama_gedung, $jumlah_lantai, $gedung_id;
    public $isEdit = false;
    public $search = '';
    public $perPage = 10;
    public $filterStatus = 'Semua';

    protected $listeners = [
        'deleteConfirmed' => 'deleteConfirmed'
    ];

    public function mount()
    {
        $this->resetInput();
    }

    public function render()
    {
        $gedungs = Gedung::query();

        if (!empty($this->search)) {
            $gedungs->where('nama_gedung', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus !== 'Semua') {
            $gedungs->where('status', $this->filterStatus);
        }

        $gedungs = $gedungs->latest()->paginate($this->perPage);

        return view('livewire.manajemen-gedung', [
            'gedungs' => $gedungs
        ]);
    }

    public function resetInput()
    {
        $this->nama_gedung = '';
        $this->jumlah_lantai = 1;
        $this->gedung_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate([
            'nama_gedung' => 'required|string|max:100',
            'jumlah_lantai' => 'required|integer|min:1',
        ]);

        Gedung::updateOrCreate(['id' => $this->gedung_id], [
            'nama_gedung' => $this->nama_gedung,
            'jumlah_lantai' => $this->jumlah_lantai,
        ]);

        $this->emit('hideModal');
        $this->resetInput();
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Data gedung berhasil disimpan.']);
    }

    public function edit($id)
    {
        $gedung = Gedung::findOrFail($id);
        $this->gedung_id = $id;
        $this->nama_gedung = $gedung->nama_gedung;
        $this->jumlah_lantai = $gedung->jumlah_lantai;
        $this->isEdit = true;
        
        $this->emit('showModal');
    }

    public function deleteConfirmed($id)
    {
        $gedung = Gedung::findOrFail($id);
        $gedung->delete();
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success', 
            'message' => 'gedung berhasil dihapus'
        ]);
    }

    public function changePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }
}