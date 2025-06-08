<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $identifier;
    public $name;
    public $password;
    public $password_confirmation;

    public function register()
    {
        // Validasi data input pengguna
        $this->validate([
            'identifier' => 'required|unique:users,identifier',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Menentukan role berdasarkan panjang identifier (NIM/NIP)
        $role = match (strlen($this->identifier)) {
            10 => 'mahasiswa',
            18 => 'dosen',
            16 => 'tendik',
            14 => 'sarpras',
            12 => 'teknisi',
            8 => 'admin',
            default => null,
        };

        // Jika role tidak dikenali, tampilkan pesan error
        if (!$role) {
            return session()->flash('error', 'Format NIM/NIP tidak dikenali.');
        }

        // Menentukan role_id berdasarkan nama role
        $roleModel = Role::where('name', $role)->first();
        if (!$roleModel) {
            return session()->flash('error', 'Role tidak ditemukan di database.');
        }
        $roleId = $roleModel->id;

        // Simpan pengguna baru ke database
        User::create([
            'identifier' => $this->identifier,
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'role_id' => $roleId,
            'profile_photo_path' => 'default/default_photo_profile.jpg', // Default untuk pengguna baru
        ]);

        // Arahkan ke halaman login setelah sukses registrasi
        session()->flash('message', 'Registrasi berhasil! Silakan login.');
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}