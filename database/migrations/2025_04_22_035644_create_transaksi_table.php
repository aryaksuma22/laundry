<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')
                ->unique()
                ->constrained('pemesanans')
                ->onDelete('cascade');
            $table->string('no_invoice')->nullable()->comment('Nomor referensi pembayaran, bisa sama dgn no_pesanan');
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->string('status_pembayaran')->default('Belum Lunas');
            $table->string('metode_pembayaran')->nullable();
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->text('catatan_transaksi')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
