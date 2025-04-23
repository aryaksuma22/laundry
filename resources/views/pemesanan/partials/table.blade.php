<form id="deleteFormPemesanan" action="{{ route('pemesanan.destroy', ['pemesanan' => 0]) }}" method="POST">
    @csrf
    @method('DELETE')
    <table class="min-w-full bg-white overflow-hidden rounded-lg shadow-sm mb-5">
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="px-4 py-2 text-left">
                    <input type="checkbox" class="form-checkbox rounded cursor-pointer" id="checkbox-all" />
                </th>
                <th class="px-4 py-2 text-left">Nama Pelanggan</th>
                <th class="px-4 py-2 text-left">No Pesanan</th>
                <th class="px-4 py-2 text-left">Tanggal</th>
                <th class="px-4 py-2 text-left">Berat (KG)</th>
                <th class="px-4 py-2 text-left">Total Harga</th>
                <th class="px-4 py-2 text-left">Status Pesanan</th>
                <th class="px-4 py-2 text-left">Alamat</th>
                <th class="px-4 py-2 text-left">Kontak</th>
                <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pemesanans as $pemesanan)
                <tr class="border hover:bg-sky-50">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="pemesanans[]" value="{{ $pemesanan->id }}"
                            class="form-checkbox rounded checkbox-row cursor-pointer" />
                    </td>
                    <td class="px-4 py-3">{{ $pemesanan->nama_pelanggan }}</td>
                    <td class="px-4 py-3">{{ $pemesanan->no_pesanan }}</td>
                    <td class="px-4 py-3">{{ $pemesanan->tanggal }}</td>
                    <td class="px-4 py-3">{{ $pemesanan->berat_pesanan }}</td>
                    <td class="px-4 py-3">{{ $pemesanan->total_harga }}</td>
                    <td class="px-4 py-3">{{ $pemesanan->status_pesanan }}</td>
                    <td class="px-4 py-3">{{ $pemesanan->alamat }}</td>
                    <td class="px-4 py-3">{{ $pemesanan->kontak }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-row">
                            <!-- Edit -->
                            <a href="{{ route('pemesanan.edit', $pemesanan->id) }}">
                                <svg class="w-6 h-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <!-- Delete -->
                            <a href="#" class="delete-pemesanan ml-4" data-id="{{ $pemesanan->id }}">
                                <svg class="w-6 h-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>

<div class="mt-4">
    {{ $pemesanans->appends([
            'search'    => request('search'),
            'perPage'   => request('perPage'),
            'sortBy'    => request('sortBy'),
            'sortOrder' => request('sortOrder'),
        ])->links() }}
</div>
