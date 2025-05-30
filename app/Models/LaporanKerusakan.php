<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubKriteria;

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
        'frekuensi_penggunaan_fasilitas',
        'tingkat_kerusakan',
        'dampak_terhadap_aktivitas_akademik',
        'tingkat_resiko_keselamatan',
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

    public function frekuensiPenggunaan()
    {
        return $this->belongsTo(SubKriteria::class, 'frekuensi_penggunaan_fasilitas');
    }

    public function tingkatKerusakan()
    {
        return $this->belongsTo(SubKriteria::class, 'tingkat_kerusakan');
    }

    public function dampakAkademik()
    {
        return $this->belongsTo(SubKriteria::class, 'dampak_terhadap_aktivitas_akademik');
    }
 
    public function resikoKeselamatan()
    {
        return $this->belongsTo(SubKriteria::class, 'tingkat_resiko_keselamatan');
    }
}