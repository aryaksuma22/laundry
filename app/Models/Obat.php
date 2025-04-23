<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obats'; // Nama tabel di database

    protected $fillable = [
        'kategori_id',
        'satuan_id',
        'kode_obat',
        'nama_obat',
        'deskripsi',
        'harga_beli',
        'harga_jual',
        'stok',
        'tanggal_kadaluarsa'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori_obat::class, 'kategori_id', 'id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan_obat::class, 'satuan_id', 'id');
    }

    public function pembelian()
    {
        return $this->hasMany(Pembelian_obat::class, 'obat_id')->onDelete('cascade');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan_obat::class, 'obat_id')->onDelete('cascade');
    }

}
