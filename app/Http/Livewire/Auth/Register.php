<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Register extends Component
{

    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';
    public $name = ''; // Menambahkan properti untuk 'name'

    public function mount()
    {
        if (auth()->user()) {
            return redirect()->intended('/dashboard');
        }
    }

    public function updatedEmail()
    {
        $this->validate(['email'=>'required|email:rfc,dns|unique:users']);
    }
    
    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',  // Validasi untuk kolom 'name'
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:passwordConfirmation|min:6',
        ]);
    
        // Membuat user baru dengan menyertakan 'name'
        $user = User::create([
            'name' => $this->name,  // Menambahkan 'name' di sini
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'remember_token' => Str::random(10),
        ]);
    
        auth()->login($user);
    
        return redirect('/profile');
    }
    

    public function render()
    {
        return view('livewire.auth.register');
    }
}
