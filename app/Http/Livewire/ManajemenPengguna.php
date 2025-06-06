<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class ManajemenPengguna extends Component
{
    use WithPagination;

    public $name, $identifier, $role_id, $user_id, $password, $password_confirmation;
    public $isEdit = false;
    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search' => ['except' => ''], 'perPage' => ['except' => 10]];

    protected $rules = [
        'name' => 'required|min:3',
        'identifier' => 'required|unique:users,identifier',
        'password' => 'required|min:6|same:password_confirmation',
        'password_confirmation' => 'required|min:6',
    ];

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->emit('open-modal');
    }

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

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->identifier = $user->identifier;
        $this->isEdit = true;
        // Emit the event instead of dispatching browser event
        $this->emit('open-modal');
    }

    public function store()
    {
        $this->validate();

        $role = match (strlen($this->identifier)) {
            10 => 'mahasiswa',
            18 => 'dosen',
            16 => 'tendik',
            14 => 'sarpras',
            12 => 'teknisi',
            default => null,
        };

        if (!$role) {
            session()->flash('error', 'Format NIM/NIP tidak dikenali.');
            return;
        }

        // Ambil role_id dari tabel roles
        $roleId = Role::where('name', $role)->value('id');
        if (!$roleId) {
            session()->flash('error', 'Role tidak ditemukan di database.');
            return;
        }

        User::create([
            'name' => $this->name,
            'identifier' => $this->identifier,
            'password' => bcrypt($this->password),
            'role_id' => $roleId,
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
            'password' => 'required|min:6|same:password_confirmation',
        ]);

        $role = match (strlen($this->identifier)) {
            10 => 'mahasiswa',
            18 => 'dosen',
            16 => 'tendik',
            14 => 'sarpras',
            12 => 'teknisi',
            default => null,
        };

        if (!$role) {
            session()->flash('error', 'Format NIM/NIP tidak dikenali.');
            return;
        }

        $roleId = Role::where('name', $role)->value('id');
        if (!$roleId) {
            session()->flash('error', 'Role tidak ditemukan di database.');
            return;
        }

        $user = User::findOrFail($this->user_id);
        $user->update([
            'name' => $this->name,
            'identifier' => $this->identifier,
            'password' => bcrypt($this->password), // Pastikan password di-hash
            'role_id' => $roleId,
            'remember_token' => \Str::random(60), // Generate token baru
        ]);

        $this->resetFields();
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
