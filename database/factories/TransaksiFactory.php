<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition()
    {
        // Kita TIDAK lagi mencoba mendapatkan pemesanan_id dari state di sini.
        // Kita ASUMSIKAN pemesanan_id akan diberikan saat pemanggilan ->create([...]).
        // Namun, kita mungkin masih butuh data Pemesanan untuk logika lain.
        // Cara terbaik adalah membuat state default jika factory dipanggil TANPA override.
        $pemesanan = Pemesanan::inRandomOrder()->first(); // Fallback jika dipanggil langsung
        if (!$pemesanan) {
            // Jika sama sekali tidak ada pemesanan, kita tidak bisa membuat transaksi yang valid.
            echo "Warning: Tidak ada Pemesanan ditemukan untuk TransaksiFactory fallback.\n";
            // Return data minimal agar tidak error, tapi ini tidak ideal.
            // Sebaiknya pastikan seeder Pemesanan berjalan dulu.
            return [
                'pemesanan_id' => 9999, // ID sementara agar tidak null, akan dioverride
                'no_invoice' => 'DUMMY-INV-' . strtoupper(Str::random(6)),
                'jumlah_dibayar' => 0,
                'status_pembayaran' => 'Belum Lunas',
                'metode_pembayaran' => null,
                'tanggal_pembayaran' => null,
                'catatan_transaksi' => 'Fallback - No valid Pemesanan found initially.',
            ];
        }

        // Override $pemesanan jika 'pemesanan_id' diberikan via state (opsional, tapi lebih aman)
        // Ini biasanya tidak diperlukan jika hanya mengandalkan override saat ->create()
        // if (isset($this->states['pemesanan_id'])) {
        //     $pemesanan = Pemesanan::find($this->states['pemesanan_id']) ?? $pemesanan;
        // }

        $totalHargaPemesanan = $pemesanan->total_harga ?? 0; // Ambil total harga dari pemesanan yang relevan

        // --- Logika penentuan status, jumlah bayar, dll. (Sama seperti sebelumnya) ---
        $statusPembayaran = $this->faker->randomElement(['Belum Lunas', 'Lunas']);
        $jumlahDibayar = 0;
        $tanggalPembayaran = null;
        $metodePembayaran = null;

        if ($statusPembayaran === 'Lunas') {
            $jumlahDibayar = $totalHargaPemesanan;
            // Pastikan tanggal bayar setelah tanggal pesan
            $tglPesan = $pemesanan->tanggal_pesan ?? $pemesanan->created_at ?? '-1 month';
            $tanggalPembayaran = $this->faker->dateTimeBetween($tglPesan, 'now');
            $metodePembayaran = $this->faker->randomElement(['Tunai', 'Transfer Bank BCA', 'QRIS', 'GoPay']);
        } else {
            if ($totalHargaPemesanan > 0 && $this->faker->boolean(30)) { // 30% chance DP
                $jumlahDibayar = $this->faker->numberBetween(1, max(1, (int)($totalHargaPemesanan * 0.8)));
                $metodePembayaran = $this->faker->randomElement(['Tunai', 'Transfer Bank BCA']);
                $tglPesan = $pemesanan->tanggal_pesan ?? $pemesanan->created_at ?? '-1 month';
                $tanggalPembayaran = $this->faker->dateTimeBetween($tglPesan, 'now'); // Tanggal DP bisa diisi
            } else {
                $jumlahDibayar = 0;
            }
        }

        return [
            // pemesanan_id TIDAK didefinisikan di sini, akan di-override oleh pemanggil
            'no_invoice'        => $pemesanan->no_pesanan . '-TRX' . strtoupper(Str::random(3)),
            'jumlah_dibayar'    => max(0, $jumlahDibayar),
            'status_pembayaran' => $statusPembayaran,
            'metode_pembayaran' => $metodePembayaran,
            'tanggal_pembayaran' => $tanggalPembayaran,
            'catatan_transaksi' => $this->faker->optional()->sentence,
        ];
    }
}
