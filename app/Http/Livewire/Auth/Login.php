<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $identifier = ''; // Ganti email menjadi identifier
    public $password = '';
    public $remember_me = false;

    // Validasi yang sesuai dengan identifier dan password
    protected $rules = [
        'identifier' => 'required|string', // Validasi untuk NIM/NIP
        'password' => 'required|min:6',
    ];

    public function mount()
    {
        // Jika sudah login, arahkan ke halaman dashboard
        if (auth()->check()) {
            return redirect()->intended('/dashboard');
        }

        // Reset nilai inputan
        $this->fill([
            'identifier' => '',
            'password' => '',
        ]);
    }

    public function login()
    {
        // Validasi kredensial pengguna
        $credentials = $this->validate();

        // Mencoba autentikasi dengan identifier (NIM/NIP) dan password
        if (auth()->attempt(['identifier' => $this->identifier, 'password' => $this->password], $this->remember_me)) {
            $user = User::where(['identifier' => $this->identifier])->first(); // Cek berdasarkan identifier (NIM/NIP)
            auth()->login($user, $this->remember_me);
            return redirect()->intended('/dashboard'); // Arahkan ke dashboard
        } else {
            // Jika login gagal, tampilkan pesan error
            return $this->addError('identifier', trans('auth.failed'));
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
