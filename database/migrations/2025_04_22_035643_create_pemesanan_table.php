<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            // --- Kolom Inti ---
            $table->id(); // Primary Key
            $table->string('no_pesanan')->unique(); // Kode Pesanan Unik (sudah ada)
            $table->string('status_pesanan')->default('Baru'); // Status (mis: Baru, Diproses, Siap Ambil, Selesai, Dibatalkan)

            // --- Informasi Pelanggan (Guest) ---
            $table->string('nama_pelanggan'); // Nama Lengkap (sudah ada)
            $table->string('kontak_pelanggan'); // Nomor Telepon / WhatsApp (rename dari 'kontak')
            $table->text('alamat_pelanggan')->nullable(); // Alamat Lengkap (ubah ke text, nullable karena mungkin tidak perlu jika diantar sendiri)

            // --- Detail Layanan & Preferensi ---
            $table->foreignId('layanan_utama_id')->constrained('layanans')->onDelete('restrict'); // Jenis Layanan Utama (mis: Kiloan, Satuan, Dry Clean). Rename dari 'layanan_id' untuk kejelasan.
            $table->string('kecepatan_layanan')->default('Reguler'); // Tingkat Kecepatan (mis: Reguler, Express, Kilat)
            $table->decimal('estimasi_berat', 8, 2)->nullable(); // Estimasi Berat (kg) - nullable karena mungkin layanan non-kiloan atau belum diisi
            $table->text('daftar_item')->nullable(); // Untuk mencatat item jika layanan satuan (nullable)
            $table->text('catatan_pelanggan')->nullable(); // Instruksi Khusus / Catatan Tambahan (nullable)

            // --- Opsi Pengambilan & Pengantaran ---
            $table->string('metode_layanan'); // (mis: 'Antar Jemput', 'Antar Sendiri Ambil Sendiri', 'Antar Sendiri Minta Diantar', 'Jemput Saja Ambil Sendiri')
            $table->date('tanggal_penjemputan')->nullable(); // Hanya jika ada permintaan jemput
            $table->string('waktu_penjemputan')->nullable(); // Slot waktu penjemputan (mis: 'Pagi 09-12', 'Siang 13-16') - nullable
            $table->text('instruksi_alamat')->nullable(); // Instruksi tambahan untuk alamat/penjemputan (nullable)

            // --- Informasi Finansial & Lainnya ---
            $table->decimal('total_harga', 15, 2)->default(0); // Total Harga (ubah ke decimal untuk presisi)
            $table->decimal('berat_final', 8, 2)->nullable(); // Berat aktual setelah ditimbang (nullable, diisi nanti)
            $table->string('kode_promo')->nullable(); // Jika menggunakan kode promo (nullable)
            // $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Jika ingin mengaitkan dengan user terdaftar nantinya
            $table->timestamp('tanggal_pesan'); // Menggantikan 'tanggal', bisa juga pakai created_at saja jika cukup
            $table->timestamp('tanggal_selesai')->nullable(); // Kapan pesanan selesai diproses
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
