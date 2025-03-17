<x-app-layout>
    <div class="h-full">
        <div class="w-5/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Edit Data Obat</h2>

                <!-- Form Edit Obat -->
                <form action="{{ route('obats.update', $obat->id) }}" method="POST" class="bg-white mb-4 p-8 rounded-lg">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/6">
                            <label for="kode_obat" class="block text-sm font-semibold">Kode Obat<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="kode_obat" id="kode_obat"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('kode_obat', $obat->kode_obat) }}" required>
                        </div>

                        <div class="w-5/6">
                            <label for="nama_obat" class="block text-sm font-semibold">Nama Obat<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="nama_obat" id="nama_obat"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                        </div>
                    </div>

                    @error('kode_obat')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('nama_obat')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="kategori_id" class="block text-sm font-semibold">Kategori<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <select name="kategori_id" id="kategori_id"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                                @foreach ($kategori_obats as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ $obat->kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-1/2">
                            <label for="satuan_id" class="block text-sm font-semibold">Satuan<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <select name="satuan_id" id="satuan_id"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                                @foreach ($satuan_obats as $satuan)
                                    <option value="{{ $satuan->id }}"
                                        {{ $obat->satuan_id == $satuan->id ? 'selected' : '' }}>
                                        {{ $satuan->nama_satuan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @error('kategori_obat')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('satuan_obat')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-9">
                        <label for="deskripsi" class="block text-sm font-semibold">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="w-full px-4 py-2 border rounded-md border-gray-300">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                    </div>

                    <div class="w-full mb-8">
                        <label for="harga_beli" class="block text-sm font-semibold">Harga Beli<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="harga_beli" id="harga_beli"
                            class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('harga_beli', $obat->harga_beli) }}" required>
                    </div>

                    <div class="w-full mb-8">
                        <label for="harga_jual" class="block text-sm font-semibold">Harga Jual<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="harga_jual" id="harga_jual"
                            class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('harga_jual', $obat->harga_jual) }}" required>
                    </div>

                    @error('harga_beli')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('harga_jual')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row gap-6 mb-8">
                        <div class="w-1/6">
                            <label for="stok" class="block text-sm font-semibold">Stok<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="number" name="stok" id="stok"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md"
                                value="{{ old('stok', $obat->stok) }}" required>
                        </div>

                        <div class="w-5/6">
                            <label for="tanggal_kadaluwarsa" class="block text-sm font-semibold">Tanggal
                                Kadaluarsa<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md"
                                value="{{ old('tanggal_kadaluarsa', $obat->tanggal_kadaluarsa) }}" required>
                        </div>
                    </div>

                    @error('stok')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('tanggal_kadaluarsa')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row gap-6 mb-8">
                        <div class="">
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold flex flex-row gap-2 items-center justify-center">
                                <svg class="w-6 h-6 text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>Edit</p>
                            </button>
                        </div>
                        <a href="{{ route('obats.index') }}">
                            <div class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Cancel</div>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new AutoNumeric('#harga_beli', {
                currencySymbol: 'Rp. ',
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                unformatOnSubmit: true
            });
            new AutoNumeric('#harga_jual', {
                currencySymbol: 'Rp. ',
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                unformatOnSubmit: true
            });
        });
    </script>

</x-app-layout>
