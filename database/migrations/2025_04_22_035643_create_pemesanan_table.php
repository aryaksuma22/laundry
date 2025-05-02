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
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('no_pesanan')->unique();
            $table->datetime('tanggal');
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('restrict');
            $table->decimal('berat_pesanan', 8, 2)->default(0);
            $table->integer('total_harga')->default(0);
            $table->string('status_pesanan');
            $table->string('alamat');
            $table->string('kontak');
            $table->timestamps();
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
