<x-app-layout>
    <div class="h-full relative">
        <div class="w-5/6 p-12 mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">Tambah Transaksi</h2>

            <form action="{{ route('transaksis.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow">
                @csrf

                {{-- PILIH PEMESANAN --}}
                <div class="mb-6">
                    <label for="pemesanan_id" class="block text-sm font-semibold">Pemesanan<span class="text-red-500">*</span></label>
                    <select name="pemesanan_id" id="pemesanan_id" required class="w-full px-4 py-2 border rounded-md border-gray-300">
                        <option value="">-- Pilih Pemesanan --</option>
                        @foreach($pemesanans as $p)
                            <option value="{{ $p->id }}"
                                    data-berat="{{ $p->berat_pesanan }}"
                                    data-harga="{{ $p->layanan->harga }}"
                                    {{ old('pemesanan_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pelanggan }} ({{ $p->no_pesanan }})
                            </option>
                        @endforeach
                    </select>
                    @error('pemesanan_id')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                {{-- INFO BERAT & TOTAL --}}
                <div class="mb-6 text-sm">
                    <p id="infoBerat">Berat Pesanan: - kg</p>
                    <p id="infoTotal">Total Harga: Rp -</p>
                </div>

                {{-- NOMINAL DIBAYAR --}}
                <div class="mb-6">
                    <label for="dibayar" class="block text-sm font-semibold">Jumlah Dibayar (Rp)<span class="text-red-500">*</span></label>
                    <input type="number" name="dibayar" id="dibayar" value="{{ old('dibayar') }}" min="0" step="1" required class="w-full px-4 py-2 border rounded-md border-gray-300">
                    @error('dibayar')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                {{-- ACTIONS --}}
                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">Simpan</button>
                    <a href="{{ route('transaksis.index') }}" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sel      = document.getElementById('pemesanan_id');
        const beratEl  = document.getElementById('infoBerat');
        const totalEl  = document.getElementById('infoTotal');

        function fmt(num){return num.toLocaleString('id-ID');}

        function refresh() {
            const opt   = sel.selectedOptions[0];
            const berat = opt?.dataset.berat  ?? 0;
            const harga = opt?.dataset.harga ?? 0;
            const total = berat * harga;
            beratEl.textContent = `Berat Pesanan: ${berat} kg`;
            totalEl.textContent = `Total Harga: Rp ${fmt(total)}`;
        }
        sel.addEventListener('change', refresh);
        refresh();
    });
    </script>
</x-app-layout>
