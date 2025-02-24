<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Tambah Data Penjualan Obat</h2>

                <!-- Form Tambah Penjualan Obat -->
                <form action="{{ route('penjualan_obats.store') }}" method="POST">
                    @csrf

                    <!-- Dropdown Pilih Obat -->
                    <div class="flex flex-col mb-4">
                        <label for="obat_id" class="block text-sm font-semibold">Nama Obat</label>
                        <select name="obat_id" id="obat_id" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="">Pilih Obat</option>
                            @foreach ($obats as $obat)
                                <option value="{{ $obat->id }}" {{ old('obat_id') == $obat->id ? 'selected' : '' }}>
                                    {{ $obat->nama_obat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Jumlah dan Harga Jual -->
                    <div class="flex flex-row mb-4 gap-6">
                        <div class="w-1/2">
                            <label for="jumlah" class="block text-sm font-semibold">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah"
                                   class="w-full px-4 py-2 border rounded-lg"
                                   value="{{ old('jumlah') }}" required>
                        </div>

                        <div class="w-1/2">
                            <label for="harga_jual" class="block text-sm font-semibold">Harga Jual</label>
                            <input type="text" name="harga_jual" id="harga_jual"
                                   class="w-full px-4 py-2 border rounded-lg"
                                   value="{{ old('harga_jual') }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- AutoNumeric untuk formatting harga_jual -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new AutoNumeric('#harga_jual', {
                currencySymbol: 'Rp. ',
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                unformatOnSubmit: true
            });
        });
    </script>
</x-app-layout>
