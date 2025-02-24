<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan_obat extends Model
{
    use HasFactory;

    protected $table = 'satuan_obats';
    
    protected $fillable = ['nama_satuan'];

    public function obats()
    {
        return $this->hasMany(Obat::class, 'satuan_id', 'id');
    }
    
}
