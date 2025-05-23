<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;
    
    protected $table = 'ruangan';
    
    protected $fillable = [
        'nama_ruangan',
        'lantai',
        'gedung_id',
        'created_at',
        'updated_at'
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    public function setNamaRuanganAttribute($value)
    {
        $this->attributes['nama_ruangan'] = strtoupper($value);
    }
}