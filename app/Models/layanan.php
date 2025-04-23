<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obats'; // Nama tabel di database

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',

    ];



}
