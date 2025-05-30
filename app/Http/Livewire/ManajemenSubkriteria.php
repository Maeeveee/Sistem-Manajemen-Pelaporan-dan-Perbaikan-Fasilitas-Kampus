<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SubKriteria;

class ManajemenSubkriteria extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $isEditing = false;

    public $currentSubKriteria = [
        'id' => null,
        'nama_subkriteria' => null,
        'nilai' => null,
    ];

    protected $rules = [
        'currentSubKriteria.nilai' => 'required|numeric|min:1|max:100',
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

    public function editSubKriteria($id)
    {
        $subkriteria = SubKriteria::findOrFail($id);
        $this->currentSubKriteria = [
            'id' => $subkriteria->id,
            'nama_subkriteria' => $subkriteria->nama_subkriteria,
            'nilai' => $subkriteria->nilai,
        ];
        $this->dispatchBrowserEvent('show-modal');
    }

    public function updateSubKriteria()
    {
        $this->validate();

        $subkriteria = SubKriteria::findOrFail($this->currentSubKriteria['id']);
        $subkriteria->update([
            'nilai' => $this->currentSubKriteria['nilai'],
        ]);

        $this->dispatchBrowserEvent('hide-modal');
        session()->flash('success', 'SubKriteria berhasil diperbarui');
    }

    public function render()
    {
        $subKriterias = SubKriteria::query()
            ->when($this->search, function ($query) {
                return $query->where('nama_subkriteria', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $totalNilai = SubKriteria::query()
            ->when($this->search, function ($query) {
                return $query->where('nama_subkriteria', 'like', '%' . $this->search . '%');
            })
            ->sum('nilai');



        return view('livewire.manajemen-subkriteria', [
            'subKriterias' => $subKriterias,
            'totalNilai' => $totalNilai
        ]);
    }
}
