<x-app-layout>
    <div class="h-full relative">
        <div class="w-5/6 p-12 mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Transaksi</h2>

            <form action="{{ route('transaksis.update', $transaksi->id) }}" method="POST" class="bg-white p-8 rounded-lg shadow">
                @csrf
                @method('PUT')

                {{-- INFO TETAP --}}
                <div class="mb-6 text-sm leading-6">
                    <p><b>Invoice:</b> {{ $transaksi->invoice }}</p>
                    <p><b>Pelanggan:</b> {{ $transaksi->pemesanan->nama_pelanggan }}</p>
                    <p><b>Layanan:</b> {{ $transaksi->pemesanan->layanan->nama_layanan }}</p>
                    <p><b>Berat:</b> {{ $transaksi->pemesanan->berat_pesanan }} kg</p>
                    @php $total = $transaksi->pemesanan->berat_pesanan * $transaksi->pemesanan->layanan->harga; @endphp
                    <p><b>Total Harga:</b> Rp{{ number_format($total,0,',','.') }}</p>
                </div>

                {{-- NOMINAL DIBAYAR --}}
                <div class="mb-6">
                    <label for="dibayar" class="block text-sm font-semibold">Jumlah Dibayar (Rp)<span class="text-red-500">*</span></label>
                    <input type="number" name="dibayar" id="dibayar" value="{{ old('dibayar',$transaksi->dibayar) }}" min="0" step="1" required class="w-full px-4 py-2 border rounded-md border-gray-300">
                    @error('dibayar')<p class="mt-1 text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                {{-- ACTIONS --}}
                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">Update</button>
                    <a href="{{ route('transaksis.index') }}" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 
