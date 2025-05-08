{{-- resources/views/pemesanan/partials/table.blade.php --}}

{{-- Form ini mungkin tidak lagi diperlukan di sini jika bulk delete dihandle murni via AJAX dan JS --}}
{{-- <form id="deleteFormPemesanan" action="{{ route('pemesanan.destroy', ['pemesanan' => 0]) }}" method="POST">
    @csrf
    @method('DELETE') --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-slate-800">
                <tr>
                    <th scope="col" class="p-4 text-left">
                        <input type="checkbox"
                            class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer"
                            id="checkbox-all" />
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No Pesanan
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Pelanggan
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tgl Pesan
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Layanan
                        Utama</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Metode</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider text-right">Total Harga
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status
                        Pesanan</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status Bayar
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    // Ambil daftar status dari controller atau definisikan di sini
                    // Pastikan variabel ini ada di scope saat partial dirender pertama kali
                    // Anda bisa mengirimkannya dari controller index method: $filterData['statusOptions']
                    // Jika tidak ada, fallback ke daftar default
                    $statusOptionsForDropdown = $filterData['statusOptions'] ?? ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];
                @endphp
                @forelse ($pemesanans as $pemesanan)
                    {{-- Tambahkan ID unik untuk setiap baris jika diperlukan untuk update sel individual --}}
                    <tr class="hover:bg-gray-50" id="pemesanan-row-{{ $pemesanan->id }}">
                        <td class="p-4 whitespace-nowrap">
                            <input type="checkbox" name="ids[]" value="{{ $pemesanan->id }}"
                                class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 checkbox-row cursor-pointer" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $pemesanan->no_pesanan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ Str::limit($pemesanan->nama_pelanggan, 20) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pemesanan->tanggal_pesan ? $pemesanan->tanggal_pesan->isoFormat('DD MMM YYYY, HH:mm') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ Str::limit($pemesanan->layananUtama->nama_layanan ?? 'N/A', 20) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pemesanan->metode_layanan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold text-right">Rp
                            {{ number_format($pemesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- START: Perubahan Status Pesanan menjadi Dropdown --}}
                            <div class="inline-flex items-center relative">
                                <select name="status_pesanan_inline"
                                    data-id="{{ $pemesanan->id }}"
                                    {{-- Menggunakan accessor yang sudah dibuat di Model Pemesanan --}}
                                    class="status-dropdown text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ $pemesanan->status_badge_class }} appearance-none py-1 pl-2 pr-7 font-semibold">
                                    @foreach ($statusOptionsForDropdown as $status)
                                        <option value="{{ $status }}"
                                            {{ $pemesanan->status_pesanan == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Spinner untuk indikator loading, awalnya hidden --}}
                                <span class="status-spinner-{{ $pemesanan->id }} ml-2 hidden">
                                    <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                            {{-- END: Perubahan Status Pesanan --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusBayar = 'Belum Lunas';
                                $statusClassBayar = 'bg-red-100 text-red-800';
                                if ($pemesanan->transaksi) {
                                    $statusBayar = $pemesanan->transaksi->status_pembayaran;
                                    if (strtolower($statusBayar) === 'lunas') {
                                        $statusClassBayar = 'bg-green-100 text-green-800';
                                    }
                                } elseif ($pemesanan->total_harga == 0 && $pemesanan->status_pesanan !== 'Dibatalkan') {
                                     // Anggap lunas jika total harga 0 dan tidak dibatalkan
                                    // $statusBayar = 'Lunas';
                                    // $statusClassBayar = 'bg-green-100 text-green-800';
                                }
                            @endphp
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClassBayar }}">
                                {{ $statusBayar }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-1.5"> {{-- Kurangi space antar ikon --}}
                                {{-- START: Tombol Quick View --}}
                                <button type="button" class="quick-view-btn text-sky-600 hover:text-sky-800 p-1 rounded hover:bg-sky-100"
                                    data-id="{{ $pemesanan->id }}" title="Lihat Detail Cepat">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                {{-- END: Tombol Quick View --}}

                                {{-- Tombol Transaksi --}}
                                @if ($pemesanan->transaksi)
                                    <a href="{{ route('transaksis.edit', $pemesanan->transaksi->id) }}"
                                        class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-100"
                                        title="Edit Transaksi (ID: {{ $pemesanan->transaksi->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('transaksis.create', ['pemesanan_id' => $pemesanan->id]) }}"
                                        class="text-blue-500 hover:text-blue-700 p-1 rounded hover:bg-blue-100"
                                        title="Buat Transaksi untuk Pesanan #{{ $pemesanan->no_pesanan }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Tombol Edit Pemesanan --}}
                                <a href="{{ route('pemesanan.edit', $pemesanan->id) }}"
                                    class="text-indigo-600 hover:text-indigo-800 p-1 rounded hover:bg-indigo-100" title="Edit Pesanan">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd"
                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>

                                {{-- Tombol Delete Pemesanan --}}
                                <a href="#" class="delete-pemesanan text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-100"
                                    data-id="{{ $pemesanan->id }}" title="Hapus Pesanan">
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
                    <tr>
                        <td colspan="10" class="text-center py-10 text-gray-500">Tidak ada data pemesanan ditemukan.
                            @if (request('search'))
                                <br>Coba ubah kata kunci pencarian Anda.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
{{-- </form> --}}
<div class="px-6 py-4 border-t border-gray-200 bg-white rounded-b-lg">
    {{ $pemesanans->appends(request()->query())->links() }}
</div>
