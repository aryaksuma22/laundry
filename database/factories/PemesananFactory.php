<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use App\Models\Layanan;
use App\Models\Transaksi; // <-- TAMBAHKAN USE STATEMENT INI
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PemesananFactory extends Factory
{
    protected $model = Pemesanan::class;

    public function definition(): array
    {
        $layanan = Layanan::inRandomOrder()->first();
        if (!$layanan) {
            throw new \Exception('Tidak ada Layanan yang tersedia di database.');
        }

        $metodeLayanan = $this->faker->randomElement(['Antar Jemput', 'Datang Langsung', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri']);
        $kecepatanLayanan = $this->faker->randomElement(['Reguler', 'Express', 'Kilat']);
        $statusPesanan = $this->faker->randomElement(['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan']);

        // --- Kalkulasi Harga & Berat/Jumlah ---
        $biayaTambahan = $this->hitungBiayaTambahan($kecepatanLayanan, $metodeLayanan);
        $beratFinal = null; // Akan diisi berat (kiloan) atau jumlah (satuan)
        $estimasiBerat = null; // Hanya relevan untuk kiloan
        $daftarItem = null; // Hanya relevan untuk satuan
        $totalHarga = 0;

        if (Str::contains(strtolower($layanan->nama_layanan), 'kiloan')) {
            $beratFinal = $this->faker->randomFloat(2, 1, 15);
            $estimasiBerat = $beratFinal - $this->faker->randomFloat(2, 0, $beratFinal * 0.2); // Estimasi sedikit lebih kecil
            $totalHarga = ($layanan->harga * $beratFinal) + $biayaTambahan['kecepatan'] + $biayaTambahan['metode'];
        } else { // Asumsi Satuan jika bukan kiloan
            $beratFinal = $this->faker->numberBetween(1, 5); // berat_final diisi jumlah item
            $daftarItem = "{$beratFinal} pcs - " . $this->faker->words(2, true);
            // Kalkulasi harga satuan: bisa (harga per item * jumlah) atau hanya harga layanan + tambahan
            // Opsi 1: Harga per item * jumlah
            $totalHarga = ($layanan->harga * $beratFinal) + $biayaTambahan['kecepatan'] + $biayaTambahan['metode'];
            // Opsi 2: Harga layanan sudah final (misal untuk Jas)
            // $totalHarga = $layanan->harga + $biayaTambahan['kecepatan'] + $biayaTambahan['metode'];
            // Pilih salah satu Opsi kalkulasi harga satuan yang sesuai
        }
        // --- Akhir Kalkulasi Harga ---

        $noPesanan = 'FCTR-' . now()->format('ymd') . strtoupper(Str::random(4));
        while (Pemesanan::where('no_pesanan', $noPesanan)->exists()) {
            $noPesanan = 'FCTR-' . now()->format('ymd') . strtoupper(Str::random(5));
        }

        $perluJemput = in_array($metodeLayanan, ['Antar Jemput', 'Minta Dijemput Ambil Sendiri']);
        $perluInstruksiAlamat = in_array($metodeLayanan, ['Antar Jemput', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri']);
        $tanggalJemput = $perluJemput ? $this->faker->dateTimeBetween('+1 day', '+5 days')->format('Y-m-d') : null;
        $waktuJemput = $perluJemput ? $this->faker->randomElement(['Pagi (09:00 - 12:00)', 'Siang (13:00 - 16:00)']) : null;

        $isSelesai = in_array($statusPesanan, ['Selesai']); // Hanya cek 'Selesai' untuk tanggal
        $tanggalSelesai = null;
        $tanggalPesan = $this->faker->dateTimeBetween('-1 month', 'now'); // Tentukan tanggal pesan dulu
        if ($isSelesai) {
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalPesan, '+1 week');
        }


        return [
            'no_pesanan' => $noPesanan,
            'status_pesanan' => $statusPesanan,
            'nama_pelanggan' => $this->faker->name(),
            'kontak_pelanggan' => $this->faker->numerify('08##########'),
            'alamat_pelanggan' => $this->faker->optional(0.7)->address(),
            'layanan_utama_id' => $layanan->id,
            'kecepatan_layanan' => $kecepatanLayanan,
            'estimasi_berat' => $estimasiBerat, // Diisi hanya jika kiloan
            'daftar_item' => $daftarItem, // Diisi hanya jika satuan
            'catatan_pelanggan' => $this->faker->optional(0.2)->paragraph(1),
            'metode_layanan' => $metodeLayanan,
            'tanggal_penjemputan' => $tanggalJemput,
            'waktu_penjemputan' => $waktuJemput,
            'instruksi_alamat' => $perluInstruksiAlamat ? $this->faker->optional(0.4)->sentence(3) : null,
            'total_harga' => max(5000, $totalHarga), // Pastikan harga minimal masuk akal
            'berat_final' => $beratFinal, // Berat (kg) atau Jumlah (pcs)
            'kode_promo' => $this->faker->optional(0.1)->lexify('PROMO??????'),
            'tanggal_pesan' => $tanggalPesan,
            'tanggal_selesai' => $tanggalSelesai,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static // Atau bisa pakai afterCreating()
    {
        return $this->afterCreating(function (Pemesanan $pemesanan) {
            // Secara otomatis buat SATU transaksi untuk SETIAP pemesanan
            Transaksi::factory()->create([
                'pemesanan_id' => $pemesanan->id,
                // Biarkan TransaksiFactory mengisi detail lainnya (jumlah_dibayar, status, dll)
                // berdasarkan data $pemesanan yang baru dibuat ini.
            ]);
        });
    }

    // Helper untuk hitung biaya tambahan (taruh di dalam class factory)
    private function hitungBiayaTambahan($kecepatan, $metode): array
    {
        $biaya = ['kecepatan' => 0, 'metode' => 0];
        // Sesuaikan dengan biaya riil Anda
        $biayaKecepatanMap = ['Reguler' => 0, 'Express' => 10000, 'Kilat' => 20000];
        $biayaMetodeMap = ['Antar Jemput' => 5000, 'Datang Langsung' => 0, 'Antar Sendiri Minta Diantar' => 3000, 'Minta Dijemput Ambil Sendiri' => 3000];

        $biaya['kecepatan'] = $biayaKecepatanMap[$kecepatan] ?? 0;
        $biaya['metode'] = $biayaMetodeMap[$metode] ?? 0;
        return $biaya;
    }
}
