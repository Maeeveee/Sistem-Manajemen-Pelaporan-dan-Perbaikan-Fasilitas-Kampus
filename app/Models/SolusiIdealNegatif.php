<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolusiIdealNegatif extends Model
{
    use HasFactory;

    protected $table = 'solusi_ideal_negatif';

    protected $fillable = [
        'nilai',
        'kriteria_id',
        'alternatif_id'
    ];

    // Relasi ke Alternatif (jika diperlukan)
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