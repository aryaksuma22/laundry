<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Tambah Data Obat</h2>

                <!-- Form Tambah Obat -->
                <form action="{{ route('obats.store') }}" method="POST">
                    @csrf

                    <div class="flex flex-row mb-4 gap-6">
                        <div class="w-1/6">
                            <label for="kode_obat" class="block text-sm font-semibold">Kode Obat</label>
                            <input type="text" name="kode_obat" id="kode_obat"
                                class="w-full px-4 py-2 border rounded-lg" value="{{ old('kode_obat') }}" required>
                        </div>

                        <div class="w-5/6">
                            <label for="nama_obat" class="block text-sm font-semibold">Nama Obat</label>
                            <input type="text" name="nama_obat" id="nama_obat"
                                class="w-full px-4 py-2 border rounded-lg" value="{{ old('nama_obat') }}" required>
                        </div>
                    </div>

                    <div class="flex flex-row mb-4 gap-6">
                        <div class="w-1/2">
                            <label for="kategori_id" class="block text-sm font-semibold">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="w-full px-4 py-2 border rounded-lg" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori_obats as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-1/2">
                            <label for="satuan_id" class="block text-sm font-semibold">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="w-full px-4 py-2 border rounded-lg" required>
                                <option value="">Pilih Satuan</option>
                                @foreach ($satuan_obats as $satuan)
                                    <option value="{{ $satuan->id }}">{{ $satuan->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-semibold">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="w-full px-4 py-2 border rounded-lg">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="flex flex-row mb-4 gap-6">
                        <div class="w-1/2">
                            <label for="harga_beli" class="block text-sm font-semibold">Harga Beli</label>
                            <input type="text" name="harga_beli" id="harga_beli"
                                class="w-full px-4 py-2 border rounded-lg" value="{{ old('harga_beli') }}" required>
                        </div>

                        <div class="w-1/2">
                            <label for="harga_jual" class="block text-sm font-semibold">Harga Jual</label>
                            <input type="text" name="harga_jual" id="harga_jual"
                                class="w-full px-4 py-2 border rounded-lg" value="{{ old('harga_jual') }}" required>
                        </div>
                    </div>

                    <div class="flex flex-row gap-6 mb-4">
                        <div class="w-1/6">
                            <label for="stok" class="block text-sm font-semibold">Stok</label>
                            <input type="number" name="stok" id="stok"
                                class="w-full px-4 py-2 border rounded-lg" value="{{ old('stok') }}" required>
                        </div>

                        <div class="w-5/6">
                            <label for="tanggal_kadaluarsa" class="block text-sm font-semibold">Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa"
                                class="w-full px-4 py-2 border rounded-lg" value="{{ old('tanggal_kadaluarsa') }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Tambah</button>
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
