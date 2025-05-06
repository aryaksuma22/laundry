<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
// use App\Models\Transaksi; // Dikomentari atau dihapus jika tidak dipakai
use App\Models\Layanan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- User Seeding ---
        User::factory()->create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.test',
            'password' => bcrypt('password'),
        ]);

        // --- Layanan Seeding ---
        Layanan::factory()->create([
            'nama_layanan' => 'Cuci Kiloan',
            'deskripsi' => 'Cuci pakaian berdasarkan berat, proses standar 2-3 hari.',
            'harga' => 7000,
        ]);
        Layanan::factory()->create([
            'nama_layanan' => 'Setrika Kiloan',
            'deskripsi' => 'Setrika pakaian berdasarkan berat, hasil rapi.',
            'harga' => 5000,
        ]);
        Layanan::factory()->create([
            'nama_layanan' => 'Cuci Satuan Kemeja',
            'deskripsi' => 'Cuci + Setrika khusus untuk 1 buah kemeja, penanganan detail.',
            'harga' => 10000,
        ]);
        Layanan::factory()->create([
            'nama_layanan' => 'Cuci Satuan Jas',
            'deskripsi' => 'Cuci + Setrika khusus untuk 1 buah jas, perawatan premium.',
            'harga' => 35000,
        ]);
        Layanan::factory()->create([
            'nama_layanan' => 'Dry Clean Jas',
            'deskripsi' => 'Pembersihan jas menggunakan metode cuci kering (tanpa air).',
            'harga' => 45000,
        ]);
        Layanan::factory()->create([
            'nama_layanan' => 'Cuci Sepatu Kanvas',
            'deskripsi' => 'Pembersihan mendalam untuk sepasang sepatu bahan kanvas.',
            'harga' => 25000,
        ]);
        Layanan::factory()->create([
            'nama_layanan' => 'Cuci Karpet Tipis (Per M²)',
            'deskripsi' => 'Cuci karpet tipis, harga dihitung per meter persegi.',
            'harga' => 15000,
        ]);
        // ... (tambahkan layanan lain) ...


        // --- Pemesanan Seeding ---
        $layananIds = Layanan::pluck('id')->toArray();

        if (!empty($layananIds)) {
            Pemesanan::factory(200)->create([
                'layanan_utama_id' => function () use ($layananIds) {
                    return $layananIds[array_rand($layananIds)];
                },
            ]);

            // --- Transaksi Seeding (DIKOMENTARI SEMENTARA) ---
            // Bagian ini seharusnya sudah dikomentari/dihapus di file Anda
            // $pemesananIds = Pemesanan::pluck('id')->toArray();
            // if (!empty($pemesananIds)) {
            //     \App\Models\Transaksi::factory(30)->create([
            //         // ...
            //     ]);
            // } else {
            //     echo "Tidak ada pemesanan untuk dibuatkan transaksi.\n";
            // }
            // --- Akhir bagian Transaksi yang dikomentari ---

        } else {
            echo "Tidak ada layanan yang tersedia untuk membuat pemesanan.\n";
        }
    }
}
