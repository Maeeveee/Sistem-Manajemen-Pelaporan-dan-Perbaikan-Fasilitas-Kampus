<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Fasilitas;
use App\Models\Gedung;
use Livewire\WithPagination;

class ManajemenFasilitas extends Component
{
    use WithPagination;
    
    public $nama_fasilitas, $lokasi, $ruang, $gedung_id;
    public $fasilitasId;
    public $isEdit = false;
    public $search = '';
    public $perPage = 10;
    public $jumlah_lantai = 1;
    
    // Untuk dropdown gedung
    public $gedungs;

    protected $rules = [
        'nama_fasilitas' => 'required|string|max:255',
        'gedung_id' => 'required|exists:gedung,id',
        'lokasi' => 'required|integer|min:1|max:100',
        'ruang' => 'required|string|max:100',
    ];

    protected $listeners = [
        'deleteConfirmed' => 'deleteConfirmed'
    ];

    public function mount()
    {
        $this->gedungs = Gedung::all();
        $this->resetInput();
    }

    public function updatedGedungId($value)
    {
        if ($value) {
            $gedung = Gedung::find($value);
            $this->jumlah_lantai = $gedung->jumlah_lantai;
            $this->rules['lokasi'] = 'required|integer|min:1|max:'.$this->jumlah_lantai;
        }
    }

    public function render()
    {
        $query = Fasilitas::with('gedung')
            ->when($this->search, function($query) {
                return $query->where('nama_fasilitas', 'like', '%'.$this->search.'%')
                             ->orWhere('ruang', 'like', '%'.$this->search.'%')
                             ->orWhere('lokasi', 'like', '%'.$this->search.'%')
                             ->orWhereHas('gedung', function($q) {
                                 $q->where('nama_gedung', 'like', '%'.$this->search.'%');
                             });
            });
        
        $fasilitas = $query->latest()->paginate($this->perPage);
        
        return view('livewire.manajemen-fasilitas', [
            'fasilitas' => $fasilitas
        ]);
    }

    public function resetInput()
    {
        $this->nama_fasilitas = '';
        $this->lokasi = '';
        $this->ruang = '';
        $this->gedung_id = null;
        $this->fasilitasId = null;
        $this->isEdit = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();
        
        Fasilitas::create([
            'nama_fasilitas' => $this->nama_fasilitas,
            'lokasi' => 'Lantai '.$this->lokasi,
            'ruang' => $this->ruang,
            'gedung_id' => $this->gedung_id,
        ]);
        
        $this->resetInput();
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success', 
            'message' => 'Fasilitas berhasil ditambahkan'
        ]);
        $this->dispatchBrowserEvent('hideModal');
        $this->resetPage();
    }

    public function edit($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $this->fasilitasId = $fasilitas->id;
        $this->nama_fasilitas = $fasilitas->nama_fasilitas;
        
        $this->lokasi = (int) str_replace('Lantai ', '', $fasilitas->lokasi);
        
        $this->ruang = $fasilitas->ruang;
        $this->gedung_id = $fasilitas->gedung_id;
        
        $gedung = Gedung::find($this->gedung_id);
        $this->jumlah_lantai = $gedung->jumlah_lantai;
        
        $this->isEdit = true;
        $this->dispatchBrowserEvent('showModal');
    }
    
    public function update()
    {
        $this->validate();

        if ($this->fasilitasId) {
            $fasilitas = Fasilitas::find($this->fasilitasId);
            
            if ($fasilitas) {
                $fasilitas->update([
                    'nama_fasilitas' => $this->nama_fasilitas,
                    'lokasi' => 'Lantai '.$this->lokasi,
                    'ruang' => $this->ruang,
                    'gedung_id' => $this->gedung_id,
                ]);

                $this->resetInput();
                $this->dispatchBrowserEvent('notify', [
                    'type' => 'success', 
                    'message' => 'Fasilitas berhasil diperbarui'
                ]);
                $this->dispatchBrowserEvent('hideModal');
            }
        }
    }
    
    public function deleteConfirmed($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success', 
            'message' => 'Fasilitas berhasil dihapus'
        ]);
    }

    public function changePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }
}