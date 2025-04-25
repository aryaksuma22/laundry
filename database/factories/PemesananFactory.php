<?php

// database/factories/PemesananFactory.php

namespace Database\Factories;

use App\Models\Pemesanan;
use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PemesananFactory extends Factory
{
    protected $model = Pemesanan::class;

    public function definition()
    {
        // 1. Grab an existing layanan or create one if none exist
        $layanan = Layanan::inRandomOrder()->first()
                   ?? Layanan::factory()->create();

        // 2. Generate a weight and compute total_harga
        $berat      = $this->faker->numberBetween(1, 10);
        $totalHarga = $layanan->harga * $berat;

        return [
            'nama_pelanggan' => $this->faker->name(),
            'no_pesanan'     => $this->faker->unique()->randomNumber(9, true),
            'tanggal'        => $this->faker->date(),
            'layanan_id'     => $layanan->id,
            'berat_pesanan'  => $berat,
            'total_harga'    => $totalHarga,
            'status_pesanan' => $this->faker->randomElement([
                                  'Pending', 'Sedang Diproses',
                                  'Terkirim', 'Diterima',
                                  'Dibatalkan', 'Dikembalikan', 'Selesai'
                                ]),
            'alamat'         => $this->faker->address(),
            'kontak'         => $this->faker->phoneNumber(),
            // timestamps will be auto-managed
        ];
    }
}
