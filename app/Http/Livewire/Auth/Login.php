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
        // Jika sudah login, arahkan ke dashboard sesuai role
        if (auth()->check()) {
            $user = auth()->user();
            switch ($user->role_id) {
                case 6: 
                    return redirect()->intended('dashboard');
                case 5: 
                    return redirect()->intended('teknisi');
                case 4: 
                    return redirect()->intended('feedback-rating');
                case 1: 
                    return redirect()->intended('pelaporan/kerusakan-fasilitas');
                case 2: 
                    return redirect()->intended('pelaporan/kerusakan-fasilitas');
                case 3: 
                    return redirect()->intended('pelaporan/kerusakan-fasilitas');
            }
        }
    }

    public function login()
    {
        // Validasi kredensial pengguna
        $credentials = $this->validate();

        // Mencoba autentikasi dengan identifier (NIM/NIP) dan password
        if (auth()->attempt(['identifier' => $this->identifier, 'password' => $this->password], $this->remember_me)) {
            $user = User::where(['identifier' => $this->identifier])->first(); // Cek berdasarkan identifier (NIM/NIP)
            auth()->login($user, $this->remember_me);

            // Redirect sesuai role
            switch ($user->role_id) {
                case 6: 
                    return redirect()->intended('dashboard');
                case 5: 
                    return redirect()->intended('teknisi');
                case 4: 
                    return redirect()->intended('feedback-rating');
                case 1: 
                    return redirect()->intended('pelaporan/kerusakan-fasilitas');
                case 2: 
                    return redirect()->intended('pelaporan/kerusakan-fasilitas');
                case 3: 
                    return redirect()->intended('pelaporan/kerusakan-fasilitas');
            }
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
