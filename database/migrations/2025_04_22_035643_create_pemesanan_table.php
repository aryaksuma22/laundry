<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->string('no_pesanan')->unique();
            $table->string('status_pesanan')->default('Baru');
            $table->string('nama_pelanggan');
            $table->string('kontak_pelanggan');
            $table->text('alamat_pelanggan')->nullable();

            // --- Detail Layanan & Preferensi ---
            $table->foreignId('layanan_utama_id')->constrained('layanans')->onDelete('restrict');
            $table->string('kecepatan_layanan')->default('Reguler');
            $table->decimal('estimasi_berat', 8, 2)->nullable();
            $table->text('daftar_item')->nullable();
            $table->text('catatan_pelanggan')->nullable();

            // --- Opsi Pengambilan & Pengantaran ---
            $table->string('metode_layanan');
            $table->date('tanggal_penjemputan')->nullable();
            $table->string('waktu_penjemputan')->nullable();
            $table->text('instruksi_alamat')->nullable();

            // --- Informasi Finansial & Lainnya ---
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('berat_final', 8, 2)->nullable();
            $table->string('kode_promo')->nullable();
            $table->timestamp('tanggal_pesan');
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
