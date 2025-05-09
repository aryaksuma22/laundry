<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Untuk seeder default

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary(); // Kunci unik untuk setting
            $table->text('value')->nullable(); // Nilai setting (bisa JSON untuk data kompleks)
            $table->string('label')->nullable(); // Label yang ramah pengguna untuk ditampilkan di UI
            $table->string('group')->default('Umum'); // Untuk mengelompokkan setting di UI
            $table->string('type')->default('text'); // Tipe input: text, number, textarea, json
            $table->text('description')->nullable(); // Deskripsi tambahan
            $table->timestamps();
        });

        // Tambahkan data default (seeder sederhana)
        $this->seedDefaults();
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

    private function seedDefaults(): void
    {
        $defaultSettings = [
            // Biaya Kecepatan
            [
                'key' => 'biaya_kecepatan_reguler',
                'value' => '0',
                'label' => 'Biaya Kecepatan Reguler',
                'group' => 'Biaya Layanan',
                'type' => 'number',
                'description' => 'Biaya tambahan untuk layanan Reguler (biasanya 0).'
            ],
            [
                'key' => 'biaya_kecepatan_express',
                'value' => '10000',
                'label' => 'Biaya Kecepatan Express',
                'group' => 'Biaya Layanan',
                'type' => 'number',
                'description' => 'Biaya tambahan untuk layanan Express.'
            ],
            [
                'key' => 'biaya_kecepatan_kilat',
                'value' => '20000',
                'label' => 'Biaya Kecepatan Kilat',
                'group' => 'Biaya Layanan',
                'type' => 'number',
                'description' => 'Biaya tambahan untuk layanan Kilat.'
            ],
            // Biaya Metode
            [
                'key' => 'biaya_metode_antar_jemput',
                'value' => '5000',
                'label' => 'Biaya Metode Antar Jemput',
                'group' => 'Biaya Layanan',
                'type' => 'number',
                'description' => 'Biaya untuk layanan Antar Jemput.'
            ],
            [
                'key' => 'biaya_metode_antar_sendiri_minta_diantar',
                'value' => '3000',
                'label' => 'Biaya Metode Antar Sendiri Minta Diantar',
                'group' => 'Biaya Layanan',
                'type' => 'number',
                'description' => 'Biaya jika pelanggan antar sendiri tapi minta diantar kembali.'
            ],
            [
                'key' => 'biaya_metode_minta_dijemput_ambil_sendiri',
                'value' => '3000',
                'label' => 'Biaya Metode Minta Dijemput Ambil Sendiri',
                'group' => 'Biaya Layanan',
                'type' => 'number',
                'description' => 'Biaya jika minta dijemput tapi ambil sendiri.'
            ],
            [
                'key' => 'biaya_metode_datang_langsung',
                'value' => '0',
                'label' => 'Biaya Metode Datang Langsung',
                'group' => 'Biaya Layanan',
                'type' => 'number',
                'description' => 'Biaya untuk layanan Datang Langsung (biasanya 0).'
            ],
            // Format Nomor Pesanan
            [
                'key' => 'prefix_no_pesanan_admin',
                'value' => 'ADM-',
                'label' => 'Prefix No. Pesanan (Admin)',
                'group' => 'Nomor Pesanan',
                'type' => 'text',
                'description' => 'Awalan untuk nomor pesanan yang dibuat oleh Admin.'
            ],
            [
                'key' => 'prefix_no_pesanan_guest',
                'value' => 'LNDRY-',
                'label' => 'Prefix No. Pesanan (Guest)',
                'group' => 'Nomor Pesanan',
                'type' => 'text',
                'description' => 'Awalan untuk nomor pesanan yang dibuat oleh Pelanggan.'
            ],
            [
                'key' => 'format_no_pesanan_random_length',
                'value' => '4',
                'label' => 'Panjang Kode Acak No. Pesanan',
                'group' => 'Nomor Pesanan',
                'type' => 'number',
                'description' => 'Jumlah karakter acak setelah tanggal pada nomor pesanan (misal: 4 untuk XXXX).'
            ],
            // Lainnya
            [
                'key' => 'nama_aplikasi',
                'value' => 'MyLaundry App',
                'label' => 'Nama Aplikasi',
                'group' => 'Umum',
                'type' => 'text',
                'description' => 'Nama aplikasi yang ditampilkan.'
            ],
        ];

        foreach ($defaultSettings as $setting) {
            DB::table('settings')->insert(
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
};
