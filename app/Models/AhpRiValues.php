<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhpRiValues extends Model
{
    use HasFactory;

    protected $table = 'ahp_ri_values';
    protected $primaryKey = 'id';
    protected $fillable = [
        'matrix_size',
        'random_index'
    ];

    // Scope untuk mencari berdasarkan ukuran matriks
    public function scopeBySize($query, $size)
    {
        return $query->where('matrix_size', $size);
    }

    // Method untuk mendapatkan RI berdasarkan ukuran matriks
    public static function getRiValue($matrixSize)
    {
        return self::bySize($matrixSize)->first()->random_index ?? 0;
    }
}
