<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolusiIdealPositif extends Model
{
    use HasFactory;

    protected $table = 'solusi_ideal_positif';

    protected $fillable = [
        'nilai',
        'kriteria_id',
        'alternatif_id'
    ];

    protected $casts = [
        'nilai' => 'decimal:8' // Casting untuk presisi
    ];

    public function kriteria()
    {
        return $this->belongsTo(\App\Models\Kriteria::class);
    }

    public function alternatif()
    {
        return $this->belongsTo(\App\Models\Alternatif::class);
    }
}