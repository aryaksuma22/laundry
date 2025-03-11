divdivdivdivdiv<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Edit Data Obat</h2>

                <!-- Form Edit Obat -->
                <form action="{{ route('obats.update', $obat->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-row mb-4 gap-6">
                        <div class="w-1/6">
                            <label for="kode_obat" class="block text-sm font-semibold">Kode Obat</label>
                            <input type="text" name="kode_obat" id="kode_obat"
                                class="w-full px-4 py-2 border rounded-lg"
                                value="{{ old('kode_obat', $obat->kode_obat) }}" required>
                        </div>

                        <div class="w-5/6">
                            <label for="nama_obat" class="block text-sm font-semibold">Nama Obat</label>
                            <input type="text" name="nama_obat" id="nama_obat"
                                class="w-full px-4 py-2 border rounded-lg"
                                value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                        </div>
                    </div>

                    @error('kode_obat')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('nama_obat')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row mb-4 gap-6">
                        <div class="w-1/2">
                            <label for="kategori_id" class="block text-sm font-semibold">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="w-full px-4 py-2 border rounded-lg"
                                required>
                                @foreach ($kategori_obats as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ $obat->kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-1/2">
                            <label for="satuan_id" class="block text-sm font-semibold">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="w-full px-4 py-2 border rounded-lg" required>
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
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('satuan_obat')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-semibold">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="w-full px-4 py-2 border rounded-lg">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                    </div>

                    <div class="flex flex-row mb-4 gap-6">
                        <div class="w-1/2">
                            <label for="harga_beli" class="block text-sm font-semibold">Harga Beli</label>
                            <input type="text" name="harga_beli" id="harga_beli"
                                class="w-full px-4 py-2 border rounded-lg"
                                value="{{ old('harga_beli', $obat->harga_beli) }}" required>
                        </div>

                        <div class="w-1/2">
                            <label for="harga_jual" class="block text-sm font-semibold">Harga Jual</label>
                            <input type="text" name="harga_jual" id="harga_jual"
                                class="w-full px-4 py-2 border rounded-lg"
                                value="{{ old('harga_jual', $obat->harga_jual) }}" required>
                        </div>
                    </div>

                    @error('harga_beli')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('harga_jual')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row gap-6 mb-4">
                        <div class="w-1/6">
                            <label for="stok" class="block text-sm font-semibold">Stok</label>
                            <input type="number" name="stok" id="stok"
                                class="w-full px-4 py-2 border rounded-lg" value="{{ old('stok', $obat->stok) }}"
                                required>
                        </div>

                        <div class="w-5/6">
                            <label for="tanggal_kadaluwarsa" class="block text-sm font-semibold">Tanggal
                                Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa"
                                class="w-full px-4 py-2 border rounded-lg"
                                value="{{ old('tanggal_kadaluarsa', $obat->tanggal_kadaluarsa) }}" required>
                        </div>
                    </div>

                    @error('stok')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('tanggal_kadaluarsa')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Update</button>
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
