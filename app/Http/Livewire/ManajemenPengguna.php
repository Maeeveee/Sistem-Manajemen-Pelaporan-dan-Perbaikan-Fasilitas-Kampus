<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Livewire\WithPagination;

class ManajemenPengguna extends Component
{
    use WithPagination;

    public $name, $identifier, $role_id, $user_id, $password;
    public $isEdit = false;
    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search' => ['except' => ''], 'perPage' => ['except' => 10]];

    protected $rules = [
        'name' => 'required|min:3',
        'identifier' => 'required|unique:users,identifier',
        'role_id' => 'required|exists:roles,id',
        'password' => 'required|min:6',
    ];

    // Specify the correct event listeners
    protected $listeners = ['open-modal' => 'openModal'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with('role');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('identifier', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.manajemen-pengguna', [
            'users' => $users,
            'roles' => Role::all(),
            'isEdit' => $this->isEdit
        ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        // Emit the event instead of dispatching browser event
        $this->emit('open-modal');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->identifier = $user->identifier;
        $this->role_id = $user->role_id;
        $this->isEdit = true;
        // Emit the event instead of dispatching browser event
        $this->emit('open-modal');
    }

    public function store()
    {
        $this->validate();
        User::create([
            'name' => $this->name,
            'identifier' => $this->identifier,
            'role_id' => $this->role_id,
            'password' => bcrypt($this->password),
        ]);
        $this->resetFields();
        // Emit the close event
        $this->emit('close-modal');
        session()->flash('message', 'Pengguna berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'identifier' => 'required|unique:users,identifier,' . $this->user_id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($this->user_id);
        $user->update([
            'name' => $this->name,
            'identifier' => $this->identifier,
            'role_id' => $this->role_id,
        ]);

        $this->resetFields();
        // Emit the close event
        $this->emit('close-modal');
        session()->flash('message', 'Pengguna berhasil diubah.');
    }

    public function delete($id)
    {
        User::destroy($id);
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }

    public function resetFields()
    {
        $this->name = '';
        $this->identifier = '';
        $this->role_id = '';
        $this->password = '';
        $this->user_id = null;
    }

    public function openModal()
    {
        // This will be called when the 'open-modal' event is emitted
    }
}