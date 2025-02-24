<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers'; // Nama tabel di database

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'telepon',
        'email',
    ];


    public function pembelian()
    {
        return $this->hasMany(Pembelian_obat::class, 'supplier_id');
    }
    
}
