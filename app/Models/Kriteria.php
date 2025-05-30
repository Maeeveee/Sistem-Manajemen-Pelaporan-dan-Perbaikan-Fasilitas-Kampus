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

    /**
     * Get the sub kriterias for the kriteria.
     */
    public function subKriterias()
    {
        return $this->hasMany(SubKriteria::class, 'kriterias_id');
    }
}