<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class Transaksi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     * Opsional jika nama tabel adalah bentuk plural dari nama model (transactions -> Transaksi).
     * Tapi lebih baik didefinisikan secara eksplisit untuk kejelasan.
     *
     * @var string
     */
    protected $table = 'transaksis';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Pastikan semua kolom yang ingin Anda isi melalui create() atau update() ada di sini.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pemesanan_id',
        'no_invoice',
        'jumlah_dibayar',
        'status_pembayaran',
        'metode_pembayaran',
        'tanggal_pembayaran',
        'catatan_transaksi',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data native.
     * Penting untuk memastikan tipe data benar saat diakses.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah_dibayar' => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'id');
    }

    // --- Helper Methods (Opsional tapi Berguna) ---

    /**
     * Cek apakah transaksi ini sudah lunas.
     *
     * @return bool
     */
    public function isLunas(): bool
    {
        return $this->status_pembayaran === 'Lunas';
    }

    /**
     * Format jumlah dibayar menjadi format Rupiah.
     * Contoh Accessor.
     *
     * @return string
     */
    public function getFormattedJumlahDibayarAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_dibayar, 0, ',', '.');
    }
}
