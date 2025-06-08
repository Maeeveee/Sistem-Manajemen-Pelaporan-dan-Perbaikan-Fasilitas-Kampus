<?php
namespace App\Http\Livewire;

use App\Models\Periode;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManajemenPeriode extends Component
{
    public $nama_periode, $tanggal_mulai, $tanggal_selesai;
    public $periodes = [];
    public $showCreateForm = false;

    protected $rules = [
        'nama_periode' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    ];

    public function mount()
    {
        $this->loadPeriode();
    }

    public function loadPeriode()
    {
        $this->periodes = Periode::orderBy('created_at', 'desc')->get();
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        $this->resetForm();
    }

    public function store()
    {

        $this->validate();

        $overlap = Periode::where(function ($query) {
            $query->whereBetween('tanggal_mulai', [$this->tanggal_mulai, $this->tanggal_selesai])
                  ->orWhereBetween('tanggal_selesai', [$this->tanggal_mulai, $this->tanggal_selesai])
                  ->orWhere(function ($q) {
                      $q->where('tanggal_mulai', '<=', $this->tanggal_mulai)
                        ->where('tanggal_selesai', '>=', $this->tanggal_selesai);
                  });
        })->exists();

        if ($overlap) {
            $this->addError('tanggal_mulai', 'Periode tumpang tindih dengan periode lain.');
            return;
        }

        try {
            Periode::create([
                'nama_periode' => $this->nama_periode,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
            ]);
            $this->resetForm();
            $this->loadPeriode();
            $this->showCreateForm = false;
            session()->flash('success', 'Periode berhasil dibuat.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat periode: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->reset(['nama_periode', 'tanggal_mulai', 'tanggal_selesai']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.manajemen-periode');
    }
}