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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_obats')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('satuan_obats')->onDelete('cascade');
            $table->string('kode_obat');
            $table->string('nama_obat');
            $table->text('deskripsi')->nullable();
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->integer('stok');
            $table->date('tanggal_kadaluarsa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
