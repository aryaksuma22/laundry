<x-app-layout>
    <div class="h-full relative">
        <div class="w-5/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Tambah Data Pemesanan</h2>
                <!-- Form Tambah Pemesanan -->
                <form action="{{ route('pemesanan.store') }}" method="POST" class="bg-white mb-4 p-8 rounded-lg">
                    @csrf

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="nama_pelanggan" class="block text-sm font-semibold">Nama Pelanggan<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                        <div class="w-1/2">
                            <label for="no_pesanan" class="block text-sm font-semibold">Nomor Pesanan<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="no_pesanan" id="no_pesanan" value="{{ old('no_pesanan') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                    </div>
                    @error('nama_pelanggan')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror
                    @error('no_pesanan')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="tanggal" class="block text-sm font-semibold">Tanggal<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                        <div class="w-1/2">
                            <label for="berat_pesanan" class="block text-sm font-semibold">Berat Pesanan (kg)<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="number" name="berat_pesanan" id="berat_pesanan" value="{{ old('berat_pesanan') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                    </div>
                    @error('tanggal')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror
                    @error('berat_pesanan')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="total_harga" class="block text-sm font-semibold">Total Harga (Rp)<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="total_harga" id="total_harga" value="{{ old('total_harga') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                        <div class="w-1/2">
                            <label for="status_pesanan" class="block text-sm font-semibold">Status Pesanan<span class="text-pink-500 ml-0.5">*</span></label>
                            <select name="status_pesanan" id="status_pesanan" class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                                <option value="">Pilih Status</option>
                                <option value="Pending" {{ old('status_pesanan') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Sedang Diproses" {{ old('status_pesanan') == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                <option value="Terkirim" {{ old('status_pesanan') == 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                                <option value="Diterima" {{ old('status_pesanan') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="Dibatalkan" {{ old('status_pesanan') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                <option value="Dikembalikan" {{ old('status_pesanan') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                <option value="Selesai" {{ old('status_pesanan') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>
                    @error('total_harga')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror
                    @error('status_pesanan')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="alamat" class="block text-sm font-semibold">Alamat<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                        <div class="w-1/2">
                            <label for="kontak" class="block text-sm font-semibold">Kontak<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="kontak" id="kontak" value="{{ old('kontak') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                    </div>
                    @error('alamat')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror
                    @error('kontak')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror

                    <div class="flex flex-row gap-4 mb-4">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold flex items-center gap-2">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/></svg>
                            <span>Tambah</span>
                        </button>
                        <a href="{{ route('pemesanan.index') }}" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold inline-flex items-center justify-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new AutoNumeric('#total_harga', {
                currencySymbol: 'Rp. ',
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                unformatOnSubmit: true
            });
        });
    </script>
</x-app-layout>
