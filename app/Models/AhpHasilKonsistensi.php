<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhpHasilKonsistensi extends Model
{
    use HasFactory;

    
    protected $table = 'ahp_hasil_konsistensi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'total_eigen',
        'lambda_max',
        'consistency_index',
        'random_index',
        'consistency_ratio',
        'is_consistent',
        'periode_id'
    ];

    // Cast attribute is_consistent sebagai boolean
    protected $casts = [
        'is_consistent' => 'boolean'
    ];

    // Scope untuk hasil konsistensi yang valid (CR <= 0.1)
    public function scopeConsistent($query)
    {
        return $query->where('is_consistent', true);
    }

    // Accessor untuk status konsistensi dalam format teks
    public function getStatusTextAttribute()
    {
        return $this->is_consistent ? 'Konsisten' : 'Tidak Konsisten';
    }

    public function scopeByPeriode($query, $periode_id)
    {
        return $query->where('periode_id', $periode_id);
    }
}
