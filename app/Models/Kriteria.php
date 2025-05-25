<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriterias';
    
    protected $fillable = [
        'nama_kriteria',
        'jenis',
        'bobot',
        'nilai_rendah',
        'nilai_sedang',
        'nilai_tinggi'
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'nilai_rendah' => 'decimal:2',
        'nilai_sedang' => 'decimal:2',
        'nilai_tinggi' => 'decimal:2'
    ];
}