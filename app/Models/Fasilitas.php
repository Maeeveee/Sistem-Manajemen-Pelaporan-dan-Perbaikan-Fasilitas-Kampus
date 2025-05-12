<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    // Pastikan nama kolom sesuai dengan nama yang digunakan di database
    protected $fillable = [
        'nama_fasilitas', 'kategori', 'status'
    ];
    
}
