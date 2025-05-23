<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;
    
    protected $table = 'fasilitas';
    
    protected $fillable = [
        'kode_fasilitas',
        'nama_fasilitas',
        'lokasi',
        'ruang',
        'ruangan_id',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function setKodeFasilitasAttribute($value)
    {
        $this->attributes['kode_fasilitas'] = strtoupper($value);
    }
}