<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubKriteria;
use App\Models\Alternatif;
use App\Models\HasilTopsis;

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
        'status_admin',
        'status_sarpras',
        'status_teknisi',
        'foto',
        'status',
        'status_perbaikan',
        'catatan_teknisi',
        'tingkat_prioritas',
        'teknisi_id'
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
    public function alternatifs()
    {
        return $this->hasMany(Alternatif::class);
    }
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
    public function laporanKerusakan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'alternatif_id');
    }
    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class, 'frekuensi_penggunaan_fasilitas'); // Sesuaikan dengan kolom foreign key yang benar
    }
    public function hasilTopsis()
    {
        return $this->hasOneThrough(
            HasilTopsis::class, Alternatif::class, 'objek_id', 'alternatif_id','id', 'id' 
        );
    }
}
