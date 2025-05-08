<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Pemesanan; // Model Pemesanan
use App\Models\Layanan;   // Model Layanan
// Tidak perlu use App\Models\Transaksi; karena tidak dipanggil langsung
use Illuminate\Support\Facades\Hash; // Gunakan Hash facade

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- User Seeding ---
        // Membuat satu user admin
        User::factory()->create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.test',
            'password' => Hash::make('password'), // Gunakan Hash::make
        ]);
        echo "Admin user created.\n";

        // --- Layanan Seeding ---
        // Membuat beberapa data layanan awal
        Layanan::factory()->create(['nama_layanan' => 'Cuci Kiloan', 'deskripsi' => 'Cuci pakaian berdasarkan berat.', 'harga' => 7000]);
        Layanan::factory()->create(['nama_layanan' => 'Setrika Kiloan', 'deskripsi' => 'Setrika pakaian berdasarkan berat.', 'harga' => 5000]);
        Layanan::factory()->create(['nama_layanan' => 'Cuci Satuan Kemeja', 'deskripsi' => 'Cuci + Setrika khusus kemeja.', 'harga' => 10000]);
        Layanan::factory()->create(['nama_layanan' => 'Cuci Satuan Jas', 'deskripsi' => 'Cuci + Setrika khusus jas.', 'harga' => 35000]);
        Layanan::factory()->create(['nama_layanan' => 'Dry Clean Jas', 'deskripsi' => 'Cuci kering khusus jas.', 'harga' => 45000]);
        Layanan::factory()->create(['nama_layanan' => 'Cuci Sepatu Kanvas', 'deskripsi' => 'Cuci sepatu kanvas.', 'harga' => 25000]);
        Layanan::factory()->create(['nama_layanan' => 'Cuci Karpet Tipis (Per M²)', 'deskripsi' => 'Cuci karpet per meter persegi.', 'harga' => 15000]);
        echo "Layanan data seeded.\n";

        // Cek apakah ada layanan sebelum membuat pemesanan
        if (Layanan::count() > 0) {
            // --- Pemesanan Seeding ---
            // Panggil PemesananFactory. Metode configure/afterCreating di dalamnya
            // akan otomatis memanggil TransaksiFactory untuk setiap pemesanan.
            $jumlahPemesananDibuat = 50; // Tentukan berapa banyak pemesanan
            echo "Attempting to create {$jumlahPemesananDibuat} Pemesanan records (each with a related Transaksi)...\n";

            Pemesanan::factory($jumlahPemesananDibuat)->create();

            echo "Successfully seeded {$jumlahPemesananDibuat} Pemesanan and their related Transaksi records.\n";
        } else {
            // Beri pesan jika tidak ada layanan, karena PemesananFactory memerlukannya
            echo "Skipping Pemesanan seeding because no Layanan records were found.\n";
        }
    }
}
