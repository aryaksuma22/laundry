<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        // Nama relasi diubah menjadi layananUtama
        // Foreign key diubah menjadi layanan_utama_id
        return $this->belongsTo(Layanan::class, 'layanan_utama_id', 'id');
    }

    /**
     * Mendapatkan data User (jika ada) yang terkait dengan Pemesanan.
     * Aktifkan jika Anda punya sistem user.
     */
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'user_id', 'id');
    // }

    /**
     * Mendapatkan semua data Transaksi yang terkait dengan Pemesanan ini.
     * (Asumsi Anda akan membuat model Transaction)
     */
    // public function transactions(): HasMany
    // {
    //     // Sesuaikan 'App\Models\Transaction' dengan namespace dan nama model transaksi Anda
    //     // Asumsi tabel 'transactions' punya kolom 'pemesanan_id'
    //     return $this->hasMany(Transaction::class, 'pemesanan_id', 'id');
    // }

    // --- Anda bisa menambahkan Accessor & Mutator atau method helper lain di sini ---

    /**
     * Contoh Accessor untuk mendapatkan total harga terformat
     */
    // public function getFormattedTotalHargaAttribute(): string
    // {
    //     return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    // }
}
