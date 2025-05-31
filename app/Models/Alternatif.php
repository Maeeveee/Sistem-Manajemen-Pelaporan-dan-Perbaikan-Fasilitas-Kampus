<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatif';
    
    protected $fillable = [
        'laporan_kerusakan_id',
        'nama_alternatif',
        'deskripsi',
        'biaya',
        'waktu_pengerjaan',
        'status'
    ];

    public function laporanKerusakan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'laporan_kerusakan_id');
    }
}