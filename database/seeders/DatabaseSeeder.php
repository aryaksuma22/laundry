<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Models\Layanan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Pemesanan::factory(20)->create();
        Layanan::create([
            'nama_layanan' => 'Regular',
            'deskripsi' => 'Layanan pengiriman standar dengan waktu estimasi 3-5 hari kerja. Biaya lebih terjangkau untuk pengiriman jarak jauh.',
            'harga' => 15000,
        ]);

        Layanan::create([
            'nama_layanan' => 'Express',
            'deskripsi' => 'Layanan pengiriman cepat dengan waktu estimasi 1-2 hari kerja. Cocok untuk pengiriman yang membutuhkan kecepatan lebih.',
            'harga' => 20000,
        ]);


        Pemesanan::factory(20)->create();
        Transaksi::factory(20)->create();
    }
}
