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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->bigInteger('no_pesanan');
            $table->date('tanggal');
            $table->integer('berat_pesanan');
            $table->integer('total_harga');
            $table->string('status_pesanan');
            $table->string('alamat');
            $table->string('kontak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
