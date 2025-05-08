<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute; // Pastikan ini di-import

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';

    protected $fillable = [
        'no_pesanan',
        'status_pesanan',
        'nama_pelanggan',
        'kontak_pelanggan',
        'alamat_pelanggan',
        'layanan_utama_id',
        'kecepatan_layanan',
        'estimasi_berat',
        'daftar_item',
        'catatan_pelanggan',
        'metode_layanan',
        'tanggal_penjemputan',
        'waktu_penjemputan',
        'instruksi_alamat',
        'total_harga',
        'berat_final',
        'kode_promo',
        'tanggal_pesan',
        'tanggal_selesai',
    ];

    protected $casts = [
        'estimasi_berat' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'berat_final' => 'decimal:2',
        'tanggal_penjemputan' => 'date',
        'tanggal_pesan' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function layananUtama(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_utama_id', 'id');
    }

    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaksi::class, 'pemesanan_id', 'id');
    }

    /**
     * Get the CSS class for the status badge.
     *
     * This accessor will be available as $pemesanan->status_badge_class
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $statusLower = strtolower($attributes['status_pesanan'] ?? '');

                switch ($statusLower) {
                    case 'baru':
                        $statusClass = 'bg-blue-100 text-blue-800';
                        break;
                    case 'menunggu diterima':
                    case 'menunggu dijemput':
                        $statusClass = 'bg-yellow-100 text-yellow-800';
                        break;
                    case 'dijemput':
                        $statusClass = 'bg-orange-100 text-orange-800';
                        break;
                    case 'diproses':
                        $statusClass = 'bg-purple-100 text-purple-800';
                        break;
                    case 'siap diantar':
                    case 'siap diambil':
                        $statusClass = 'bg-teal-100 text-teal-800';
                        break;
                    case 'selesai':
                        $statusClass = 'bg-green-100 text-green-800';
                        break;
                    case 'dibatalkan':
                        $statusClass = 'bg-red-100 text-red-800';
                        break;
                    default:
                        $statusClass = 'bg-gray-100 text-gray-800';
                        break;
                }
                return $statusClass;
            }
        );
    }
}
