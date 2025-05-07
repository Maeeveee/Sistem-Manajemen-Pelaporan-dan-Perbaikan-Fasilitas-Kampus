<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class Users extends Component
{
    public function render()
    {
        $users = User::all(); // Ambil semua user dari database
        return view('livewire.users', [
            'users' => $users
        ]);
    }
}
