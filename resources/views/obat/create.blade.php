<x-app-layout>
    <div class=" h-full relative">
        <div class="w-5/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Tambah Data Obat</h2>
                <!-- Form Tambah Obat -->
                <form action="{{ route('obats.store') }}" method="POST" class="bg-white mb-4 p-8 rounded-lg">
                    @csrf

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/6">
                            <label for="kode_obat" class="block text-sm font-semibold">Kode Obat<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="kode_obat" id="kode_obat"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('kode_obat') }}" required>
                        </div>
                        <div class="w-5/6">
                            <label for="nama_obat" class="block text-sm font-semibold">Nama Obat<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="nama_obat" id="nama_obat"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('nama_obat') }}" required>
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

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="kategori_id" class="block text-sm font-semibold">Kategori<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <select name="kategori_id" id="kategori_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori_obats as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-1/2">
                            <label for="satuan_id" class="block text-sm font-semibold">Satuan<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <select name="satuan_id" id="satuan_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                                <option value="">Pilih Satuan</option>
                                @foreach ($satuan_obats as $satuan)
                                    <option value="{{ $satuan->id }}">{{ $satuan->nama_satuan }}</option>
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


                    <div class="mb-8">
                        <label for="deskripsi" class="block text-sm font-semibold">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="w-full px-4 py-2 border rounded-md border-gray-300">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="w-full mb-8">
                        <label for="harga_beli" class="block text-sm font-semibold">Harga Beli<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="harga_beli" id="harga_beli"
                            class="w-full px-4 py-2 border rounded-md border-gray-300" value="{{ old('harga_beli') }}"
                            required>
                    </div>

                    <div class="w-full mb-8">
                        <label for="harga_jual" class="block text-sm font-semibold">Harga Jual<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="harga_jual" id="harga_jual"
                            class="w-full px-4 py-2 border rounded-md border-gray-300" value="{{ old('harga_jual') }}"
                            required>
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


                    <div class="flex flex-row gap-6 mb-8">
                        <div class="w-1/6">
                            <label for="stok" class="block text-sm font-semibold">Stok<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="number" name="stok" id="stok"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" value="{{ old('stok') }}"
                                required>
                        </div>

                        <div class="w-5/6">
                            <label for="tanggal_kadaluarsa" class="block text-sm font-semibold">Tanggal
                                Kadaluarsa<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('tanggal_kadaluarsa') }}" required>
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
                    <div class="flex flex-row gap-4 mb-4">
                        <div class="">
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold flex flex-row gap-2 items-center">
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>Tambah</p>
                            </button>
                        </div>
                        <a href="{{ route('obats.index') }}">
                            <div class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold">
                                Cancel
                            </div>
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
