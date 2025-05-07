<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'identifier',
        'name',
        'password',
        'role_id',  // Pastikan menggunakan role_id
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi dengan role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

        // Menonaktifkan kolom created_at dan updated_at
        public $timestamps = false;
}

