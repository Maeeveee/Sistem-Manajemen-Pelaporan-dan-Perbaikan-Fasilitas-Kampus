<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan';

    protected $fillable = [
        'nama_pelapor',
        'identifier',
        'gedung_id',
        'ruangan_id',
        'lantai',
        'fasilitas_id',
        'deskripsi',
        'foto',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke tabel gedung
    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    // Relasi ke tabel ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    // Relasi ke tabel fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class);
    }
}