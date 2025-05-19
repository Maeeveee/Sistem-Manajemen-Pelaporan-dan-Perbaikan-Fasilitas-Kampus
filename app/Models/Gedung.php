<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;
    
    protected $table = 'gedung';
    
    protected $fillable = [
        'nama_gedung',
        'jumlah_lantai',
        'created_at',
        'updated_at'
    ];

    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class);
    }
}