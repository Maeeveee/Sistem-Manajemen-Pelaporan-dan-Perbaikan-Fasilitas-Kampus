<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Kriteria;

class AhpPerbandinganKriteria extends Model
{
    use HasFactory;

    protected $table = 'ahp_perbandingan_kriteria';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kriteria_pertama_id',
        'kriteria_kedua_id',
        'nilai_perbandingan',
        'periode_id'
    ];

    // Relasi dengan model Kriterias untuk kriteria pertama
    public function kriteriaPertama()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_pertama_id');
    }

    // Relasi dengan model Kriterias untuk kriteria kedua
    public function kriteriaKedua()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_kedua_id');
    }

    // Scope untuk mencari perbandingan antara dua kriteria tertentu
    public function scopeBetweenKriteria($query, $kriteria1, $kriteria2)
    {
        return $query->where(function($q) use ($kriteria1, $kriteria2) {
            $q->where('kriteria_pertama_id', $kriteria1)
              ->where('kriteria_kedua_id', $kriteria2);
        })->orWhere(function($q) use ($kriteria1, $kriteria2) {
            $q->where('kriteria_pertama_id', $kriteria2)
              ->where('kriteria_kedua_id', $kriteria1);
        });
    }

    public function scopeByPeriode($query, $periode_id)
    {
        return $query->where('periode_id', $periode_id);
    }
}
