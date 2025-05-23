<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Fasilitas;
use App\Models\Ruangan;
use App\Models\Gedung;
use Livewire\WithPagination;

class ManajemenFasilitas extends Component
{
    use WithPagination;
    
    public $kode_fasilitas, $nama_fasilitas, $jumlah, $ruangan_id;
    public $fasilitasId;
    public $isEdit = false;
    public $search = '';
    public $perPage = 10;
    
    // Untuk sistem cascade dropdown
    public $gedung_id, $lantai;
    public $gedung = [];
    public $lantaiList = [];
    public $ruanganList = [];

    protected function rules()
    {
        return [
            'kode_fasilitas' => 'required|string|max:20|unique:fasilitas,kode_fasilitas,' . $this->fasilitasId,
            'nama_fasilitas' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'gedung_id' => 'required|exists:gedung,id',
            'ruangan_id' => 'required|exists:ruangan,id',
        ];
    }

    protected $listeners = [
        'deleteConfirmation' => 'deleteConfirmation',
        'deleteConfirmed' => 'deleteConfirmed'
    ];

    public function mount()
    {
        $this->loadGedung();
        $this->resetInput();
    }

    public function loadGedung()
    {
        $this->gedung = Gedung::orderBy('nama_gedung')->get();
    }

    // Method yang dipanggil ketika gedung berubah
    public function updatedGedungId()
    {
        $this->lantai = '';
        $this->ruangan_id = '';
        $this->lantaiList = [];
        $this->ruanganList = [];
        
        if ($this->gedung_id) {
            $this->lantaiList = Ruangan::where('gedung_id', $this->gedung_id)
                ->distinct()
                ->orderBy('lantai')
                ->pluck('lantai')
                ->toArray();
        }
    }

    // Method yang dipanggil ketika lantai berubah
    public function updatedLantai()
    {
        $this->ruangan_id = '';
        $this->ruanganList = [];
        
        if ($this->gedung_id && $this->lantai) {
            $this->ruanganList = Ruangan::where('gedung_id', $this->gedung_id)
                ->where('lantai', $this->lantai)
                ->orderBy('nama_ruangan')
                ->get();
        }
    }

    public function render()
    {
        $query = Fasilitas::with('ruangan.gedung')
            ->when($this->search, function($query) {
                return $query->where('kode_fasilitas', 'like', '%'.$this->search.'%')
                             ->orWhere('nama_fasilitas', 'like', '%'.$this->search.'%')
                             ->orWhereHas('ruangan', function($q) {
                                 $q->where('nama_ruangan', 'like', '%'.$this->search.'%')
                                   ->orWhereHas('gedung', function($q2) {
                                       $q2->where('nama_gedung', 'like', '%'.$this->search.'%');
                                   });
                             });
            });
        
        $fasilitas = $query->latest()->paginate($this->perPage);
        
        return view('livewire.manajemen-fasilitas', [
            'fasilitas' => $fasilitas
        ]);
    }

    public function resetInput()
    {
        $this->kode_fasilitas = '';
        $this->nama_fasilitas = '';
        $this->jumlah = 1;
        $this->gedung_id = '';
        $this->lantai = '';
        $this->ruangan_id = '';
        $this->fasilitasId = null;
        $this->isEdit = false;
        $this->lantaiList = [];
        $this->ruanganList = [];
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $this->kode_fasilitas = strtoupper($this->kode_fasilitas);
        
        Fasilitas::create([
            'kode_fasilitas' => $this->kode_fasilitas,
            'nama_fasilitas' => $this->nama_fasilitas,
            'jumlah' => $this->jumlah,
            'ruangan_id' => $this->ruangan_id,
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
        $fasilitas = Fasilitas::with('ruangan.gedung')->findOrFail($id);
        
        $this->fasilitasId = $fasilitas->id;
        $this->kode_fasilitas = $fasilitas->kode_fasilitas;
        $this->nama_fasilitas = $fasilitas->nama_fasilitas;
        $this->jumlah = $fasilitas->jumlah;
        $this->ruangan_id = $fasilitas->ruangan_id;
        
        // Set gedung dan lantai berdasarkan ruangan yang dipilih
        $this->gedung_id = $fasilitas->ruangan->gedung_id;
        $this->lantai = $fasilitas->ruangan->lantai;
        
        // Load lantai dan ruangan untuk editing
        $this->updatedGedungId();
        $this->updatedLantai();
        
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
                    'kode_fasilitas' => $this->kode_fasilitas,
                    'nama_fasilitas' => $this->nama_fasilitas,
                    'jumlah' => $this->jumlah,
                    'ruangan_id' => $this->ruangan_id,
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
    
    public function deleteConfirmation($id)
    {
        $this->dispatchBrowserEvent('deleteConfirmation', $id);
    }
    
    public function deleteConfirmed($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success', 
            'message' => 'Fasilitas berhasil dihapus'
        ]);
        $this->resetPage();
    }

    public function changePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }

    public function setKodeFasilitasAttribute($value)
    {
        $this->attributes['kode_fasilitas'] = strtoupper($value);
    }
}