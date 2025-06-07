<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periodes';
    protected $fillable = ['nama_periode', 'tanggal_mulai', 'tanggal_selesai'];

    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'periode_id');
    }
}
