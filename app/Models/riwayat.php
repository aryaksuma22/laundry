<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;

    protected $table = 'pemesanans'; // Nama tabel di database

    protected $fillable = [
        'nama_pelanggan',
        'tanggal_selesai',
        'status_pesanan',
        'alamat',
        'kontak',
    ];

}
