<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans'; // Nama tabel di database

    protected $fillable = [
        'nama_pelanggan',
        'no_pesanan',
        'tanggal',
        'layanan_id',
        'berat_pesanan',
        'total_harga',
        'status_pesanan',
        'alamat',
        'kontak',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'id');
    }
}
