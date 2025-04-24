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
        // Ambil satu pemesanan yang sudah ada
        $pemesanan = Pemesanan::inRandomOrder()->first();

        // Ambil satu layanan yang sudah ada
        $layanan = Layanan::inRandomOrder()->first();

        // Hitung total harga berdasar berat di pemesanan + harga layanan
        $totalHarga = $pemesanan->berat_pesanan * $layanan->harga;

        return [
            'pemesanan_id' => $pemesanan->id,
            'layanan_id'   => $layanan->id,
            'invoice'      => 'INV-' . $this->faker->unique()->numerify('########'),
            // total bayar acak antara 0 hingga total harga
            'dibayar'      => $this->faker->numberBetween(0, $totalHarga),
            // timestamps akan otomatis di-handle oleh Eloquent
        ];
    }
}
