{{-- pemesanan/partials/table.blade.php --}}
<form id="deleteFormPemesanan" action="{{ route('pemesanan.destroy', ['pemesanan' => 0]) }}" method="POST">
    @csrf
    @method('DELETE')
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-slate-800">
            <tr>
                <th scope="col" class="p-4 text-left">
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer" id="checkbox-all" />
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No Pesanan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Pelanggan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tgl Pesan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Layanan Utama</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Metode</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Berat Final (Kg)</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Harga</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Kontak</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($pemesanans as $pemesanan)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 whitespace-nowrap">
                        <input type="checkbox" name="ids[]" value="{{ $pemesanan->id }}" class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 checkbox-row cursor-pointer" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pemesanan->no_pesanan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $pemesanan->nama_pelanggan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pemesanan->tanggal_pesan ? $pemesanan->tanggal_pesan->isoFormat('DD MMM YYYY, HH:mm') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pemesanan->layananUtama->nama_layanan ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pemesanan->metode_layanan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $pemesanan->berat_final !== null ? number_format($pemesanan->berat_final, 2, ',', '.') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold text-right">Rp {{ number_format($pemesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{-- >>> TAMBAHKAN BLOK PHP INI <<< --}}
                        @php
                            $statusClass = '';
                            // Konversi ke huruf kecil untuk perbandingan case-insensitive
                            $statusLower = strtolower($pemesanan->status_pesanan ?? '');

                            switch ($statusLower) {
                                case 'baru':
                                    $statusClass = 'bg-blue-100 text-blue-800'; // Baru masuk
                                    break;
                                case 'menunggu diterima': // Pelanggan antar sendiri, laundry menunggu
                                case 'menunggu dijemput': // Laundry harus jemput
                                    $statusClass = 'bg-yellow-100 text-yellow-800'; // Status menunggu/pending action
                                    break;
                                case 'diproses':
                                    $statusClass = 'bg-purple-100 text-purple-800'; // Sedang aktif dikerjakan
                                    break;
                                case 'siap diantar': // Selesai, menunggu diantar oleh laundry
                                case 'siap diambil': // Selesai, menunggu diambil pelanggan
                                    $statusClass = 'bg-teal-100 text-teal-800'; // Siap untuk langkah selanjutnya
                                    break;
                                case 'selesai': // Sudah diterima/diambil pelanggan
                                    $statusClass = 'bg-green-100 text-green-800'; // Selesai sepenuhnya
                                    break;
                                case 'dibatalkan':
                                    $statusClass = 'bg-red-100 text-red-800'; // Dibatalkan
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-800'; // Status tidak dikenal
                                    break;
                            }
                        @endphp
                        {{-- >>> BATAS BLOK PHP <<< --}}
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ $pemesanan->status_pesanan ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pemesanan->kontak_pelanggan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium  ">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('pemesanan.edit', $pemesanan->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg></a>
                            <a href="#" class="delete-pemesanan text-red-600 hover:text-red-900" data-id="{{ $pemesanan->id }}" title="Hapus"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></a>
                        </div>
                    </td>
                </tr>
            @empty
                 <tr><td colspan="11" class="text-center py-10 text-gray-500">Tidak ada data pemesanan ditemukan.@if(request('search'))<br>Coba ubah kata kunci pencarian Anda.@endif</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</form>
<div class="px-6 py-4 border-t border-gray-200">
    {{ $pemesanans->appends(request()->query())->links() }}
</div>
