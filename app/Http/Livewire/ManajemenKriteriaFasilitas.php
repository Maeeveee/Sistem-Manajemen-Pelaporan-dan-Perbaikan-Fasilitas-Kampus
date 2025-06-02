<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kriteria;

class ManajemenKriteriaFasilitas extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $isEditing = false;
    
    public $currentKriteria = [
        'id' => null,
        'nama_kriteria' => null,
        'jenis' => null,
        'bobot' => null,
        'nilai_rendah' => null,
        'nilai_sedang' => null,
        'nilai_tinggi' => null
    ];


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function changePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage();
    }

    public function editKriteria($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $this->currentKriteria = [
            'id' => $kriteria->id,
            'nama_kriteria' => $kriteria->nama_kriteria,
            'jenis' => $kriteria->jenis,
            'bobot' => $kriteria->bobot,
            'nilai_rendah' => $kriteria->nilai_rendah,
            'nilai_sedang' => $kriteria->nilai_sedang,
            'nilai_tinggi' => $kriteria->nilai_tinggi
        ];
        $this->dispatchBrowserEvent('show-modal');
    }

    public function updateKriteria()
    {
        $this->validate();

        $kriteria = Kriteria::findOrFail($this->currentKriteria['id']);
        $kriteria->update([
            'bobot' => $this->currentKriteria['bobot'],
            'nilai_rendah' => $this->currentKriteria['nilai_rendah'],
            'nilai_sedang' => $this->currentKriteria['nilai_sedang'],
            'nilai_tinggi' => $this->currentKriteria['nilai_tinggi']
        ]);

        $this->dispatchBrowserEvent('hide-modal');
        session()->flash('success', 'Kriteria berhasil diperbarui');
    }

    public function render()
    {
        $kriterias = Kriteria::query()
            ->when($this->search, function ($query) {
                return $query->where('nama_kriteria', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.manajemen-kriteria-fasilitas', [
            'kriterias' => $kriterias
        ]);
    }
}