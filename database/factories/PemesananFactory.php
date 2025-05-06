<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use App\Models\Layanan; // Tetap diperlukan untuk mendapatkan harga/referensi
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Import Str untuk Str::random

class PemesananFactory extends Factory
{
    protected $model = Pemesanan::class;

    public function definition(): array
    {
        // 1. Ambil Layanan Utama yang sudah ada (diasumsikan sudah di-seed sebelumnya)
        //    Jika tidak ada, seeding akan gagal (ini lebih baik daripada membuat layanan baru di sini)
        $layanan = Layanan::inRandomOrder()->first();

        // Handle jika tidak ada layanan sama sekali
        if (!$layanan) {
            throw new \Exception('Tidak ada Layanan yang tersedia di database untuk membuat Pemesanan Factory.');
        }

        // 2. Tentukan data generik untuk field baru
        $metodeLayanan = $this->faker->randomElement(['Antar Jemput', 'Datang Langsung', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri']);
        $kecepatanLayanan = $this->faker->randomElement(['Reguler', 'Express', 'Kilat']);
        $statusPesanan = $this->faker->randomElement(['Baru', 'Menunggu Dijemput','Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan']);

        // Generate berat (bisa untuk estimasi atau final, atau keduanya sama untuk seeding)
        $berat = $this->faker->randomFloat(2, 1, 15); // Berat antara 1.00 s/d 15.00 kg

        // Hitung total harga (contoh sederhana, bisa lebih kompleks)
        // Untuk seeding, kita bisa asumsikan harga berdasarkan berat * harga layanan
        // Di aplikasi nyata, harga final diinput Admin
        $totalHarga = $layanan->harga * $berat;
        // Jika layanan bukan kiloan, mungkin perlu logika berbeda atau set manual di seeder
        // if ($layanan->nama_layanan == 'Cuci Satuan Kemeja') { $totalHarga = $layanan->harga; $berat = null; }

        // Generate nomor pesanan unik
        $noPesanan = 'FCTR-' . now()->format('ymd') . strtoupper(Str::random(4));
        while (Pemesanan::where('no_pesanan', $noPesanan)->exists()) {
            $noPesanan = 'FCTR-' . now()->format('ymd') . strtoupper(Str::random(5));
        }

        // Menentukan apakah perlu tanggal jemput
        $perluJemput = in_array($metodeLayanan, ['Antar Jemput', 'Minta Dijemput Ambil Sendiri']);
        $tanggalJemput = $perluJemput ? $this->faker->dateTimeBetween('+1 day', '+5 days')->format('Y-m-d') : null;
        $waktuJemput = $perluJemput ? $this->faker->randomElement(['Pagi (09:00 - 12:00)', 'Siang (13:00 - 16:00)']) : null;

        // Menentukan apakah sudah selesai
        $isSelesai = in_array($statusPesanan, ['Selesai', 'Diambil', 'Sudah Diantar']);
        $tanggalSelesai = $isSelesai ? $this->faker->dateTimeBetween('+3 days', '+10 days') : null;


        return [
            // --- Kolom Inti ---
            'no_pesanan' => $noPesanan, // Gunakan generator unik
            'status_pesanan' => $statusPesanan,

            // --- Informasi Pelanggan ---
            'nama_pelanggan' => $this->faker->name(),
            'kontak_pelanggan' => $this->faker->numerify('08##########'), // Format nomor HP Indonesia
            'alamat_pelanggan' => $this->faker->address(), // Tetap nullable, tapi kita isi saja

            // --- Detail Layanan & Preferensi ---
            'layanan_utama_id' => $layanan->id, // Ganti dari layanan_id
            'kecepatan_layanan' => $kecepatanLayanan, // Field baru
            'estimasi_berat' => $berat, // Field baru (bisa sama dengan berat_final untuk seeding)
            'daftar_item' => $this->faker->optional(0.3)->sentence(5), // 30% kemungkinan diisi
            'catatan_pelanggan' => $this->faker->optional(0.2)->paragraph(1), // 20% kemungkinan diisi

            // --- Opsi Pengambilan & Pengantaran ---
            'metode_layanan' => $metodeLayanan, // Field baru
            'tanggal_penjemputan' => $tanggalJemput, // Field baru (nullable, conditional)
            'waktu_penjemputan' => $waktuJemput, // Field baru (nullable, conditional)
            'instruksi_alamat' => $this->faker->optional(0.4)->sentence(3), // 40% kemungkinan diisi jika perlu jemput/antar

            // --- Informasi Finansial & Lainnya ---
            'total_harga' => $totalHarga, // Kalkulasi sederhana untuk seeding
            'berat_final' => $berat, // Field baru (disamakan dengan estimasi untuk seeding)
            'kode_promo' => $this->faker->optional(0.1)->lexify('PROMO??????'), // 10% kemungkinan diisi
            'tanggal_pesan' => $this->faker->dateTimeBetween('-1 month', 'now'), // Ganti dari tanggal
            'tanggal_selesai' => $tanggalSelesai, // Field baru (nullable, conditional)

            // Timestamps (created_at, updated_at) akan diisi otomatis oleh Eloquent
        ];
    }
}
