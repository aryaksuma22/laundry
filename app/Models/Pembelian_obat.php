<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian_obat extends Model
{
    use HasFactory;

    protected $table = 'pembelian_obats'; // Nama tabel di database

    protected $fillable = [
        'obat_id',
        'supplier_id',
        'jumlah',
        'harga_beli',
        'total_harga',
        'tanggal_pembelian',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

}
