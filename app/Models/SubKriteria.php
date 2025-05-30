<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sub_kriterias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_subkriteria',
        'nilai',
        'kriterias_id'
    ];

    /**
     * Get the kriteria that owns the sub kriteria.
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriterias_id');
    }
}