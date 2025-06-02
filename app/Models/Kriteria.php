<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_kriteria',
        'bobot'
    ];
    protected $table = 'kriterias';
    protected $primaryKey = 'id';

    /**
     * Get the sub kriterias for the kriteria.
     */
    public function subKriterias()
    {
        return $this->hasMany(SubKriteria::class, 'kriterias_id');
    }

    // Relasi dengan model AhpBobotKriteria
    // App/Models/Kriteria.php
public function bobotKriteria()
{
    return $this->hasMany(AhpBobotKriteria::class, 'id');
}

    // Relasi dengan model AhpPerbandinganKriteria sebagai kriteria pertama
    public function perbandinganSebagaiPertama()
    {
        return $this->hasMany(AhpPerbandinganKriteria::class, 'kriteria_pertama_id');
    }

    // Relasi dengan model AhpPerbandinganKriteria sebagai kriteria kedua
    public function perbandinganSebagaiKedua()
    {
        return $this->hasMany(AhpPerbandinganKriteria::class, 'kriteria_kedua_id');
    }

    // Accessor untuk bobot dalam bentuk desimal (0-1)
    public function getBobotDecimalAttribute()
    {
        return $this->bobot / 100;
    }
}