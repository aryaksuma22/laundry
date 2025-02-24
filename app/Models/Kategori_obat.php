<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori_obat extends Model
{
    use HasFactory;

    protected $table = 'kategori_obats';

    protected $fillable = ['nama_kategori'];

    public function obats()
    {
        return $this->hasMany(Obat::class, 'kategori_id', 'id');
    }
}
