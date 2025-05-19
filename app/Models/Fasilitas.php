<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;
    
    protected $table = 'fasilitas';
    
    protected $fillable = [
        'nama_fasilitas',
        'lokasi',
        'ruang',
        'gedung_id',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }
}