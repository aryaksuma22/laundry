{{-- resources/views/pemesanan/partials/quick-view-modal-content.blade.php --}}
@if(isset($pemesanan))
    {{-- Header Informasi Utama --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Pesanan</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $pemesanan->no_pesanan }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tgl Pesan</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $pemesanan->tanggal_pesan ? $pemesanan->tanggal_pesan->isoFormat('dddd, DD MMM YYYY, HH:mm') : '-' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $pemesanan->nama_pelanggan }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kontak</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $pemesanan->kontak_pelanggan }}</dd>
        </div>
        @if($pemesanan->alamat_pelanggan)
        <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $pemesanan->alamat_pelanggan }}</dd>
        </div>
        @endif
    </div>

    {{-- Detail Layanan & Preferensi --}}
    <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Detail Layanan</h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Layanan Utama</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->layananUtama->nama_layanan ?? 'N/A' }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Kecepatan</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->kecepatan_layanan }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Metode</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->metode_layanan }}</dd>
        </div>
        @if($pemesanan->estimasi_berat)
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Est. Berat</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ number_format($pemesanan->estimasi_berat, 1) }} Kg</dd>
        </div>
        @endif
        @if($pemesanan->tanggal_penjemputan)
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Jadwal Jemput</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->tanggal_penjemputan->isoFormat('DD MMM YYYY') }}, {{ $pemesanan->waktu_penjemputan }}</dd>
        </div>
        @endif
        @if($pemesanan->instruksi_alamat)
        <div class="sm:col-span-2">
            <dt class="font-medium text-gray-500 dark:text-gray-400">Instruksi Alamat</dt>
            <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap">{{ $pemesanan->instruksi_alamat }}</dd>
        </div>
        @endif
        @if($pemesanan->daftar_item)
        <div class="sm:col-span-2">
            <dt class="font-medium text-gray-500 dark:text-gray-400">Daftar Item</dt>
            <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap">{{ $pemesanan->daftar_item }}</dd>
        </div>
        @endif
        @if($pemesanan->catatan_pelanggan)
        <div class="sm:col-span-2">
            <dt class="font-medium text-gray-500 dark:text-gray-400">Catatan Pelanggan</dt>
            <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap">{{ $pemesanan->catatan_pelanggan }}</dd>
        </div>
        @endif
    </div>

    {{-- Informasi Finansial & Status --}}
    <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Status & Finansial</h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Status Pesanan</dt>
            <dd class="mt-1">
                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pemesanan->status_badge_class }}">
                    {{ $pemesanan->status_pesanan }}
                </span>
            </dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Status Bayar</dt>
            <dd class="mt-1">
                @php
                    $statusBayar = 'Belum Lunas';
                    $statusClassBayar = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                    if ($pemesanan->transaksi) {
                        $statusBayar = $pemesanan->transaksi->status_pembayaran;
                        if (strtolower($statusBayar) === 'lunas') {
                            $statusClassBayar = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                        }
                    }
                @endphp
                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClassBayar }}">
                    {{ $statusBayar }}
                </span>
            </dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Berat Final</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">
                 @if (Str::contains(strtolower($pemesanan->layananUtama->nama_layanan ?? ''), 'kiloan') && $pemesanan->berat_final !== null)
                    {{ number_format($pemesanan->berat_final, 2, ',', '.') }} Kg
                @elseif (!Str::contains(strtolower($pemesanan->layananUtama->nama_layanan ?? ''), 'kiloan') && $pemesanan->berat_final !== null)
                    {{ (int) $pemesanan->berat_final }} Pcs
                @else
                    -
                @endif
            </dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Total Harga</dt>
            <dd class="mt-1 text-gray-900 dark:text-white font-bold">Rp {{ number_format($pemesanan->total_harga ?? 0, 0, ',', '.') }}</dd>
        </div>
        @if($pemesanan->kode_promo)
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Kode Promo</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->kode_promo }}</dd>
        </div>
        @endif
        @if($pemesanan->tanggal_selesai)
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Tgl Selesai</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->tanggal_selesai->isoFormat('dddd, DD MMM YYYY, HH:mm') }}</dd>
        </div>
        @endif
    </div>

    {{-- Detail Transaksi (jika ada) --}}
    @if($pemesanan->transaksi)
    <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Detail Transaksi</h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm mb-4">
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">No. Invoice</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->transaksi->no_invoice }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Jumlah Dibayar</dt>
            <dd class="mt-1 text-gray-900 dark:text-white font-semibold">Rp {{ number_format($pemesanan->transaksi->jumlah_dibayar ?? 0, 0, ',', '.') }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Metode Bayar</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->transaksi->metode_pembayaran ?: '-' }}</dd>
        </div>
        <div>
            <dt class="font-medium text-gray-500 dark:text-gray-400">Tgl Bayar</dt>
            <dd class="mt-1 text-gray-900 dark:text-white">{{ $pemesanan->transaksi->tanggal_pembayaran ? $pemesanan->transaksi->tanggal_pembayaran->isoFormat('dddd, DD MMM YYYY, HH:mm') : '-' }}</dd>
        </div>
        @if($pemesanan->transaksi->catatan_transaksi)
        <div class="sm:col-span-2">
            <dt class="font-medium text-gray-500 dark:text-gray-400">Catatan Transaksi</dt>
            <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap">{{ $pemesanan->transaksi->catatan_transaksi }}</dd>
        </div>
        @endif
    </div>
    @endif

    {{-- Tombol Aksi di Dalam Modal --}}
    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('pemesanan.edit', $pemesanan->id) }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Edit Selengkapnya
        </a>
        <button type="button" data-modal-hide="quickViewModal"
            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150">
            Tutup
        </button>
    </div>

@else
    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
        Data pemesanan tidak ditemukan atau gagal dimuat.
    </div>
@endif
