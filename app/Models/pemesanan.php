<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini jika Anda membuat model Transaction

class Pemesanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'pemesanans'; // Pastikan ini sesuai dengan nama tabel di migrasi

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_pesanan',           // Kode unik pesanan
        'status_pesanan',       // Status terkini (Baru, Diproses, dll.)
        'nama_pelanggan',       // Nama pelanggan
        'kontak_pelanggan',     // Kontak pelanggan (WA/Telepon)
        'alamat_pelanggan',     // Alamat (nullable)
        'layanan_utama_id',     // Foreign key ke jenis layanan utama
        'kecepatan_layanan',    // Tingkat kecepatan (Reguler, Express)
        'estimasi_berat',       // Estimasi berat awal (nullable)
        'daftar_item',          // Daftar item jika layanan satuan (nullable)
        'catatan_pelanggan',    // Catatan khusus dari pelanggan (nullable)
        'metode_layanan',       // Metode (Antar Jemput, Datang Langsung, dll.)
        'tanggal_penjemputan',  // Tanggal jemput (nullable)
        'waktu_penjemputan',    // Slot waktu jemput (nullable)
        'instruksi_alamat',     // Instruksi tambahan alamat (nullable)
        'total_harga',          // Total harga final (diisi/update kemudian)
        'berat_final',          // Berat aktual setelah ditimbang (nullable, diisi kemudian)
        'kode_promo',           // Kode promo yang digunakan (nullable)
        'tanggal_pesan',        // Timestamp kapan pesanan dibuat (alternatif: bisa pakai created_at)
        'tanggal_selesai',      // Timestamp kapan pesanan selesai (nullable, diisi kemudian)
        // 'user_id',           // Jika dihubungkan ke user terdaftar (nullable)
    ];

    /**
     * Atribut yang harus di-cast ke tipe data native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estimasi_berat' => 'decimal:2',    // Cast ke desimal dengan 2 angka di belakang koma
        'total_harga' => 'decimal:2',       // Cast ke desimal dengan 2 angka di belakang koma
        'berat_final' => 'decimal:2',       // Cast ke desimal dengan 2 angka di belakang koma
        'tanggal_penjemputan' => 'date',     // Cast ke objek Carbon Date
        'tanggal_pesan' => 'datetime',   // Cast ke objek Carbon DateTime
        'tanggal_selesai' => 'datetime',   // Cast ke objek Carbon DateTime (nullable)
        // 'created_at' => 'datetime',    // Otomatis oleh Eloquent jika menggunakan timestamps()
        // 'updated_at' => 'datetime',    // Otomatis oleh Eloquent jika menggunakan timestamps()
    ];

    /**
     * Mendapatkan data Layanan utama yang terkait dengan Pemesanan.
     */
    public function layananUtama(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_utama_id', 'id');
    }

    public function transaksi(): HasOne
    {
        // Argumen: Class Model terkait, foreign_key (opsional jika mengikuti konvensi 'pemesanan_id'), local_key (opsional jika 'id')
        return $this->hasOne(Transaksi::class, 'pemesanan_id', 'id');
    }
}
