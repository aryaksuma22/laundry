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

                    {{-- ROW 1: Nama + No Pesanan --}}
                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="nama_pelanggan" class="block text-sm font-semibold">Nama Pelanggan<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('nama_pelanggan', $pemesanan->nama_pelanggan) }}" required>
                            @error('nama_pelanggan')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>

                        <div class="w-1/2">
                            <label for="no_pesanan" class="block text-sm font-semibold">Nomor Pesanan<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="no_pesanan" id="no_pesanan"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('no_pesanan', $pemesanan->no_pesanan) }}" required>
                            @error('no_pesanan')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- ROW 2: Pilih Layanan + Tanggal --}}
                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="layanan_id" class="block text-sm font-semibold">Layanan<span class="text-pink-500 ml-0.5">*</span></label>
                            <select name="layanan_id" id="layanan_id" class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->id }}"
                                        {{ old('layanan_id', $pemesanan->layanan_id) == $layanan->id ? 'selected' : '' }}
                                    >{{ $layanan->nama_layanan }} — Rp {{ number_format($layanan->harga) }}</option>
                                @endforeach
                            </select>
                            @error('layanan_id')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="w-1/2">
                            <label for="tanggal" class="block text-sm font-semibold">Tanggal<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="date" name="tanggal" id="tanggal"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('tanggal', $pemesanan->tanggal) }}" required>
                            @error('tanggal')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- ROW 3: Berat + Total Harga --}}
                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="berat_pesanan" class="block text-sm font-semibold">Berat Pesanan (kg)<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="number" name="berat_pesanan" id="berat_pesanan" min="0"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('berat_pesanan', $pemesanan->berat_pesanan) }}" required>
                            @error('berat_pesanan')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="w-1/2">
                            <label for="total_harga" class="block text-sm font-semibold">Total Harga (Rp)<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="total_harga" id="total_harga"
                                class="w-full px-4 py-2 border rounded-md border-gray-300 bg-gray-100"
                                value="{{ old('total_harga', $pemesanan->total_harga) }}" readonly>
                            @error('total_harga')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- ROW 4: Status + Alamat --}}
                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="status_pesanan" class="block text-sm font-semibold">Status Pesanan<span class="text-pink-500 ml-0.5">*</span></label>
                            <select name="status_pesanan" id="status_pesanan" class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                                <option value="">-- Pilih Status --</option>
                                @foreach(['Pending','Sedang Diproses','Terkirim','Diterima','Dibatalkan','Dikembalikan','Selesai'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('status_pesanan', $pemesanan->status_pesanan) == $status ? 'selected' : '' }}
                                    >{{ $status }}</option>
                                @endforeach
                            </select>
                            @error('status_pesanan')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="w-1/2">
                            <label for="alamat" class="block text-sm font-semibold">Alamat<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="alamat" id="alamat"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('alamat', $pemesanan->alamat) }}" required>
                            @error('alamat')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- ROW 5: Kontak --}}
                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="kontak" class="block text-sm font-semibold">Kontak<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="kontak" id="kontak"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('kontak', $pemesanan->kontak) }}" required>
                            @error('kontak')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- ACTION --}}
                    <div class="flex flex-row gap-6 mb-8">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold inline-flex items-center gap-2">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                            </svg>
                            <span>Edit</span>
                        </button>
                        <a href="{{ route('pemesanan.index') }}" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 inline-flex items-center justify-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const beratEl       = document.getElementById('berat_pesanan');
        const layananSelect = document.getElementById('layanan_id');

        // Initialize AutoNumeric on total_harga
        const anTotal = new AutoNumeric('#total_harga', {
            currencySymbol: 'Rp. ',
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            unformatOnSubmit: true
        });

        // Build a JS map: layanan_id => harga
        const hargaMapping = {};
        @foreach($layanans as $layanan)
            hargaMapping['{{ $layanan->id }}'] = {{ $layanan->harga }};
        @endforeach

        function updateTotal() {
            const berat      = parseFloat(beratEl.value) || 0;
            const layananId  = layananSelect.value;
            const harga      = hargaMapping[layananId] || 0;
            anTotal.set(berat * harga);
        }

        beratEl.addEventListener('input', updateTotal);
        layananSelect.addEventListener('change', updateTotal);
        updateTotal();
    });
    </script>
</x-app-layout>
