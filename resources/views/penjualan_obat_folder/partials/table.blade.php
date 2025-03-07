{{-- Tabel Data Obat --}}
<form id="deleteForm" action="{{ route('penjualan_obats.destroy', ['penjualan_obat' => 0]) }}" method="POST">
    @csrf
    @method('DELETE')
    <table class="min-w-full bg-white overflow-hidden rounded-xl shadow-sm mb-5">
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="px-4 py-2 text-left">
                    <input type="checkbox" class="form-checkbox rounded-[4px]" id="checkbox-all" />
                </th>
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">Nama Obat</th>
                <th class="px-4 py-2 text-left">Jumlah</th>
                <th class="px-4 py-2 text-left">Harga Jual</th>
                <th class="px-4 py-2 text-left">Total Harga</th>
                <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan_obats as $penjualan_obat)
                <tr class="border">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="penjualan_obats[]" value="{{ $penjualan_obat->id }}"
                            class="form-checkbox rounded-[5px] checkbox-row" />
                    </td>
                    <td class="px-4 py-3">{{ $penjualan_obat->id }}</td>
                    <td class="px-4 py-3">
                        {{ $penjualan_obat->obat_id ? $penjualan_obat->obat->nama_obat : 'N/A' }}
                    </td>
                    <td class="px-4 py-3">{{ $penjualan_obat->jumlah }}</td>
                    <td class="px-4 py-3">{{ $penjualan_obat->harga_jual }}</td>
                    <td class="px-4 py-3">{{ $penjualan_obat->total_harga }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-row">
                            <a href="{{ route('penjualan_obats.edit', $penjualan_obat->id) }}">
                                <svg class="w-6 h-6 text-yellow-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                            {{-- Added Features, delete the data --}}
                            <a href="#" class="delete-penjualan_obat" data-id="{{ $penjualan_obat->id }}">
                                <svg class="w-6 h-6 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>

<!-- Pagination links -->
<div class="mt-4">
    {{ $penjualan_obats->appends([
            'search' => request('search'),
            'perPage' => request('perPage'),
            'sortBy' => request('sortBy'),
            'sortOrder' => request('sortOrder'),
        ])->links() }}
</div>
