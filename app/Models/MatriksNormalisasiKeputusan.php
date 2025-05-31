<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriksNormalisasiKeputusan extends Model
{
    use HasFactory;

    protected $table = 'matriks_normalisasi_keputusan';

    protected $fillable = [
        'nilai',
        'alternatif_id',
        'kriteria_id'
    ];

    // Relasi ke Alternatif
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    // Relasi ke Kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}