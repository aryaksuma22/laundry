<x-app-layout>
    <div class="h-full">
        <div class="w-5/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Edit Data Pemesanan</h2>

                <!-- Form Edit Pemesanan -->
                <form action="{{ route('pemesanan.update', $pemesanan->id) }}" method="POST"
                    class="bg-white mb-4 p-8 rounded-lg">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="nama_pelanggan" class="block text-sm font-semibold">Nama Pelanggan<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('nama_pelanggan', $pemesanan->nama_pelanggan) }}" required>
                        </div>

                        <div class="w-1/2">
                            <label for="no_pesanan" class="block text-sm font-semibold">Nomor Pesanan<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="no_pesanan" id="no_pesanan"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('no_pesanan', $pemesanan->no_pesanan) }}" required>
                        </div>
                    </div>

                    @error('nama_pelanggan')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('no_pesanan')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-md relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="tanggal" class="block text-sm font-semibold">Tanggal<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="date" name="tanggal" id="tanggal"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('tanggal', $pemesanan->tanggal) }}" required>
                        </div>

                        <div class="w-1/2">
                            <label for="berat_pesanan" class="block text-sm font-semibold">Berat Pesanan (kg)<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="number" name="berat_pesanan" id="berat_pesanan"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('berat_pesanan', $pemesanan->berat_pesanan) }}" required>
                        </div>
                    </div>

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="total_harga" class="block text-sm font-semibold">Total Harga (Rp)<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="total_harga" id="total_harga"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('total_harga', $pemesanan->total_harga) }}" required>
                        </div>

                        <div class="w-1/2">
                            <label for="status_pesanan" class="block text-sm font-semibold">Status Pesanan<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <select name="status_pesanan" id="status_pesanan"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                                <option value="Pending"
                                    {{ old('status_pesanan', $pemesanan->status_pesanan) == 'Pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="Sedang Diproses"
                                    {{ old('status_pesanan', $pemesanan->status_pesanan) == 'Sedang Diproses' ? 'selected' : '' }}>
                                    Sedang Diproses</option>
                                <option value="Terkirim"
                                    {{ old('status_pesanan', $pemesanan->status_pesanan) == 'Terkirim' ? 'selected' : '' }}>
                                    Terkirim</option>
                                <option value="Diterima"
                                    {{ old('status_pesanan', $pemesanan->status_pesanan) == 'Diterima' ? 'selected' : '' }}>
                                    Diterima</option>
                                <option value="Dibatalkan"
                                    {{ old('status_pesanan', $pemesanan->status_pesanan) == 'Dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                                <option value="Dikembalikan"
                                    {{ old('status_pesanan', $pemesanan->status_pesanan) == 'Dikembalikan' ? 'selected' : '' }}>
                                    Dikembalikan</option>
                                <option value="Selesai"
                                    {{ old('status_pesanan', $pemesanan->status_pesanan) == 'Selesai' ? 'selected' : '' }}>
                                    Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="alamat" class="block text-sm font-semibold">Alamat<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="alamat" id="alamat"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('alamat', $pemesanan->alamat) }}" required>
                        </div>

                        <div class="w-1/2">
                            <label for="kontak" class="block text-sm font-semibold">Kontak<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="kontak" id="kontak"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('kontak', $pemesanan->kontak) }}" required>
                        </div>
                    </div>

                    <div class="flex flex-row gap-6 mb-8">
                        <div class="">
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold flex flex-row gap-2 items-center justify-center">
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>Edit</p>
                            </button>
                        </div>
                        <a href="{{ route('pemesanan.index') }}">
                            <div class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Cancel</div>
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
