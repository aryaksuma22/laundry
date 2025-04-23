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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pemesanan_id');
            $table->string('nama_pelanggan');
            $table->string('alamat');
            $table->string('invoice');
            $table->bigInteger('layanan_id');
            $table->integer('berat_pesanan');
            $table->integer('total_harga');
            $table->integer('dibayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
