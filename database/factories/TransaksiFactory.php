<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\Pemesanan;
use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition()
    {
        $pemesanan = Pemesanan::inRandomOrder()->first();
        return [
            'pemesanan_id' => $pemesanan->id,
            'layanan_id'   => $pemesanan->layanan_id,
            'invoice'      => 'INV-'.$this->faker->unique()->numerify('########'),
            'total_harga'  => $pemesanan->berat_pesanan * $pemesanan->layanan->harga,
            'dibayar'      => $this->faker->numberBetween(0, $pemesanan->total_harga),
        ];
    }
}
