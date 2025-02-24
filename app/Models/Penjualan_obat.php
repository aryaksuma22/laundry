<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan_obat extends Model
{
    use HasFactory;

    protected $table = 'penjualan_obats';

    protected $fillable = [
        'obat_id',
        'jumlah',
        'harga_jual',
        'total_harga',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id', 'id');
    }

}
