<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Relasi dengan User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}