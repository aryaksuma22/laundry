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
        Schema::create('pembelian_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained();    
            $table->foreignId('supplier_id')->constrained();
            $table->integer('jumlah');
            $table->integer('harga_beli');
            $table->integer('total_harga');
            $table->date('tanggal_pembelian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_obats');
    }
};
