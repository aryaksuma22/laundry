<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class PemesananFactory extends Factory
{
    protected $model = Pemesanan::class;

    public function definition()
    {
        return [
            'nama_pelanggan' => $this->faker->name,
            'no_pesanan' => $this->faker->unique()->randomNumber(9),
            'tanggal' => $this->faker->date(),
            'berat_pesanan' => $this->faker->randomDigitNotNull,
            'total_harga' => $this->faker->numberBetween(50000, 200000),
            'status_pesanan' => $this->faker->randomElement(['Pending', 'Sedang Diproses', 'Terkirim', 'Diterima', 'Dibatalkan', 'Dikembalikan', 'Selesai']),
            'alamat' => $this->faker->address,
            'kontak' => $this->faker->phoneNumber,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
