<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriksNormalisasiBobotKeputusan extends Model
{
    use HasFactory;

    protected $table = 'matriks_normalisasi_bobot_keputusan';

    protected $fillable = [
        'nilai',
        'alternatif_id',
        'kriteria_id'
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}