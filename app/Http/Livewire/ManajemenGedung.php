<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Gedung;
use App\Models\Ruangan;
use Illuminate\Validation\Rule;

class ManajemenGedung extends Component
{
    use WithPagination;

    // Properties untuk Gedung
    public $kode_gedung, $nama_gedung, $jumlah_lantai, $gedung_id;
    public $isEditGedung = false;
    public $search = '';
    public $perPage = 10;

    // Properties untuk Ruangan
    public $selected_gedung_id, $selectedGedung;
    public $ruangan_id, $nama_ruangan, $lantai;
    public $searchRuangan = '';
    public $ruangans = [];
    public $isEditRuangan = false;

    // Tambahkan property untuk tracking modal state
    public $showDaftarRuanganModal = false;

    protected $listeners = [
        'refreshRuangans' => 'loadRuangans',
        'deleteGedungConfirmation' => 'deleteGedungConfirmation',
        'deleteGedungConfirmed' => 'deleteGedungConfirmed',
        'deleteRuanganConfirmation' => 'deleteRuanganConfirmation',
        'deleteRuanganConfirmed' => 'deleteRuanganConfirmed'
    ];

    public function updatedSearchRuangan()
    {
        $this->loadRuangans();
    }

    public function resetModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        $gedungs = Gedung::query()
            ->when($this->search, function ($query) {
                return $query->where('nama_gedung', 'like', '%' . $this->search . '%')
                    ->orWhere('kode_gedung', 'like', '%' . $this->search . '%');
            })
            ->withCount('ruangans')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.manajemen-gedung', [
            'gedungs' => $gedungs
        ]);
    }

    public function mount()
    {
        $this->resetInputGedung();
        $this->resetInputRuangan();
    }

    // GEDUNG METHODS
    public function resetInputGedung()
    {
        $this->kode_gedung = '';
        $this->nama_gedung = '';
        $this->jumlah_lantai = 1;
        $this->gedung_id = null;
        $this->isEditGedung = false;
    }

    public function storeGedung()
    {
        $this->validate([
            'kode_gedung' => 'required|string|max:20|unique:gedung,kode_gedung,' . $this->gedung_id,
            'nama_gedung' => 'required|string|max:100',
            'jumlah_lantai' => 'required|integer|min:1',
        ]);

        Gedung::updateOrCreate(['id' => $this->gedung_id], [
            'kode_gedung' => $this->kode_gedung,
            'nama_gedung' => $this->nama_gedung,
            'jumlah_lantai' => $this->jumlah_lantai,
        ]);

        $this->dispatchBrowserEvent('hideModal', ['modalId' => 'gedungModal']);
        $this->resetInputGedung();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success', 
            'message' => $this->isEditGedung ? 'Gedung berhasil diperbarui' : 'Gedung berhasil ditambahkan'
        ]);
    }

    public function editGedung($id)
    {
        $gedung = Gedung::findOrFail($id);
        $this->gedung_id = $id;
        $this->kode_gedung = $gedung->kode_gedung;
        $this->nama_gedung = $gedung->nama_gedung;
        $this->jumlah_lantai = $gedung->jumlah_lantai;
        $this->isEditGedung = true;

        $this->dispatchBrowserEvent('showModal', ['modalId' => 'gedungModal']);
    }

    public function deleteGedungConfirmation($id)
    {
        $this->dispatchBrowserEvent('deleteGedungConfirmation', $id);
    }

    public function deleteGedungConfirmed($id)
    {
        $gedung = Gedung::findOrFail($id);
        $gedung->delete();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success', 
            'message' => 'Gedung berhasil dihapus'
        ]);
        $this->resetPage();
    }

    // RUANGAN METHODS
    public function resetInputRuangan()
    {
        $this->ruangan_id = null;
        $this->nama_ruangan = '';
        $this->lantai = 1;
        $this->isEditRuangan = false;
    }

    public function resetSearchRuangan()
    {
        $this->searchRuangan = '';
        $this->loadRuangans();
    }

    public function showRuanganModal($gedungId)
    {
        $this->selected_gedung_id = $gedungId;
        $this->selectedGedung = Gedung::withCount('ruangans')->findOrFail($gedungId);
        $this->searchRuangan = '';
        $this->showDaftarRuanganModal = true;
        $this->loadRuangans();

        $this->dispatchBrowserEvent('showModal', ['modalId' => 'daftarRuanganModal']);
    }

    public function closeDaftarRuanganModal()
    {
        $this->showDaftarRuanganModal = false;
        $this->searchRuangan = '';
        $this->ruangans = [];
    }

    public function loadRuangans()
    {
        if (!$this->selected_gedung_id) {
            $this->ruangans = [];
            return;
        }

        $this->ruangans = Ruangan::where('gedung_id', $this->selected_gedung_id)
            ->when($this->searchRuangan, function ($query) {
                return $query->where('nama_ruangan', 'like', '%' . $this->searchRuangan . '%');
            })
            ->orderBy('lantai')
            ->orderBy('nama_ruangan')
            ->get();
    }

    public function showTambahRuanganModal()
    {
        $this->resetInputRuangan();
        $this->dispatchBrowserEvent('hideModal', ['modalId' => 'daftarRuanganModal']);
        
        $this->dispatchBrowserEvent('showModalDelayed', ['modalId' => 'ruanganModal', 'delay' => 300]);
    }

    public function storeRuangan()
    {
        // Uppercase nama_ruangan sebelum validasi
        $this->nama_ruangan = strtoupper($this->nama_ruangan);
        
        $this->validate([
            'nama_ruangan' => [
                'required',
                'string',
                'max:100',
                Rule::unique('ruangan')->where(function ($query) {
                    return $query->where('gedung_id', $this->selected_gedung_id)
                                ->where('lantai', $this->lantai);
                })->ignore($this->ruangan_id)
            ],
            'lantai' => 'required|integer|min:1',
        ]);

        Ruangan::updateOrCreate(['id' => $this->ruangan_id], [
            'nama_ruangan' => $this->nama_ruangan,
            'lantai' => $this->lantai,
            'gedung_id' => $this->selected_gedung_id,
        ]);

        $this->dispatchBrowserEvent('hideModal', ['modalId' => 'ruanganModal']);
        $this->loadRuangans();
        $this->dispatchBrowserEvent('showModalDelayed', ['modalId' => 'daftarRuanganModal']);
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success', 
            'message' => $this->isEditRuangan ? 'Ruangan berhasil diperbarui' : 'Ruangan berhasil ditambahkan'
        ]);
    }

    public function editRuangan($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $this->ruangan_id = $id;
        $this->nama_ruangan = $ruangan->nama_ruangan;
        $this->lantai = $ruangan->lantai;
        $this->isEditRuangan = true;

        $this->dispatchBrowserEvent('hideModal', ['modalId' => 'daftarRuanganModal']);
        $this->dispatchBrowserEvent('showModalDelayed', ['modalId' => 'ruanganModal', 'delay' => 300]);
    }

    public function deleteRuanganConfirmation($id)
    {
        $this->dispatchBrowserEvent('deleteRuanganConfirmation', $id);
    }

    public function deleteRuanganConfirmed($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        $this->loadRuangans();
        $this->dispatchBrowserEvent('alert', [
            'type' => 'success', 
            'message' => 'Ruangan berhasil dihapus'
        ]);
    }

    public function changePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }
}