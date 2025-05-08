{{-- transaksi/partials/table.blade.php --}}
{{-- Form untuk bulk delete (opsional, jika tidak semua transaksi boleh dihapus massal) --}}
<form id="deleteFormTransaksi" action="{{ route('transaksis.massDestroy') }}" method="POST">
    {{-- Ganti route --}}
    @csrf
    @method('DELETE')
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-slate-800">
                <tr>
                    <th scope="col" class="p-4 text-left">
                        <input type="checkbox"
                            class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer"
                            id="checkbox-all" />
                    </th>
                    {{-- Sesuaikan Kolom Tabel Transaksi --}}
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tgl
                        Transaksi</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No Invoice
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No Pesanan
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Pelanggan
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider text-right">
                        Total Tagihan</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider text-right">
                        Jumlah Dibayar</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status Bayar
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Metode Bayar
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tgl Bayar
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{-- Ganti variabel loop --}}
                @forelse ($transaksis as $transaksi)
                    <tr
                        class="hover:bg-gray-50 {{ $transaksi->status_pembayaran == 'Lunas' ? 'bg-green-50' : 'bg-red-50' }}">
                        <td class="p-4 whitespace-nowrap">
                            {{-- Ganti value jika ID transaksi berbeda dari pemesanan --}}
                            <input type="checkbox" name="ids[]" value="{{ $transaksi->id }}"
                                class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 checkbox-row cursor-pointer" />
                        </td>
                        {{-- Sesuaikan Data Kolom --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaksi->created_at->isoFormat('DD MMM YYYY, HH:mm') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $transaksi->no_invoice ?: $transaksi->pemesanan->no_pesanan ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-700 hover:text-indigo-900">
                            @if ($transaksi->pemesanan)
                                <a href="{{ route('pemesanan.edit', $transaksi->pemesanan->id) }}"
                                    title="Lihat Detail Pesanan">
                                    {{ $transaksi->pemesanan->no_pesanan }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $transaksi->pemesanan->nama_pelanggan ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-semibold text-right">Rp
                            {{ number_format($transaksi->pemesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold text-right">
                            {{-- Menggunakan accessor jika ada: $transaksi->formatted_jumlah_dibayar --}}
                            Rp {{ number_format($transaksi->jumlah_dibayar ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = '';
                                switch (strtolower($transaksi->status_pembayaran ?? '')) {
                                    case 'lunas':
                                        $statusClass = 'bg-green-100 text-green-800';
                                        break;
                                    case 'belum lunas':
                                        $statusClass = 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        break;
                                }
                            @endphp
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $transaksi->status_pembayaran ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaksi->metode_pembayaran ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaksi->tanggal_pembayaran ? $transaksi->tanggal_pembayaran->isoFormat('DD MMM YYYY, HH:mm') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                {{-- Link ke detail transaksi jika ada halaman show --}}
                                {{-- <a href="{{ route('transaksi.show', $transaksi->id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail Transaksi">
                                    <svg class="w-5 h-5" <!-- ikon mata/detail --> </svg>
                                </a> --}}
                                <a href="{{ route('transaksis.edit', $transaksi->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900" title="Edit Transaksi">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd"
                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                                {{-- Ganti class dan data-id --}}
                                <a href="#" class="delete-transaksi text-red-600 hover:text-red-900"
                                    data-id="{{ $transaksi->id }}" title="Hapus Transaksi">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    {{-- Ganti colspan & teks --}}
                    <tr>
                        <td colspan="11" class="text-center py-10 text-gray-500">Tidak ada data transaksi ditemukan.
                            @if (request('search'))
                                <br>Coba ubah kata kunci pencarian Anda.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>
<div class="px-6 py-4 border-t border-gray-200">
    {{-- Ganti variabel --}}
    {{ $transaksis->appends(request()->query())->links() }}
</div>
