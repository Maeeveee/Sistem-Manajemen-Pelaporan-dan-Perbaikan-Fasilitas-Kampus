<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;
    
    protected $table = 'gedung';
    
    protected $fillable = [
        'kode_gedung',
        'nama_gedung',
        'jumlah_lantai',
    ];

    public function ruangans()
    {
        return $this->hasMany(Ruangan::class, 'gedung_id');
    }
}