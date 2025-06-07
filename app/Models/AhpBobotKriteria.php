<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhpBobotKriteria extends Model
{
    use HasFactory;

     protected $table = 'ahp_bobot_kriteria';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kriteria_id',
        'bobot_ahp',
        'eigen_value',
        'periode_id'
    ];

    // Relasi dengan model Kriterias
    public function kriterias()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    // Accessor untuk bobot dalam persentase
    public function getBobotPersenAttribute()
    {
        return $this->bobot_ahp * 100;
    }

    // Scope untuk mengurutkan berdasarkan bobot tertinggi
    public function scopeOrderByBobot($query, $direction = 'desc')
    {
        return $query->orderBy('bobot_ahp', $direction);
    }
    
    // Scope untuk mendapatkan data berdasarkan periode
    public function scopeByPeriode($query, $periode_id)
    {
        return $query->where('periode_id', $periode_id);
    }
}
