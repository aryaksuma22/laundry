<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis'; // Nama tabel di database

    protected $fillable = [
        'pemesanan_id',
        'nama_pelanggan',
        'alamat',
        'invoice',
        'layanan_id',
        'berat_pesenan',
        'total_herga',
        'dibayar',
    ];
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'id');
    }

}
