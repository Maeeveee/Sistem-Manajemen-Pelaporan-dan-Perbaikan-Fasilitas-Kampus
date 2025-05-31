<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriksKeputusan extends Model
{
    use HasFactory;

    protected $table = 'matriks_keputusan';

    protected $fillable = [
        'nilai',
        'kriteria_id'
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}