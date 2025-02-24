<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Tambah Data Pembelian Obat</h2>

                <!-- Form Tambah Pembelian Obat -->
                <form action="{{ route('pembelian_obats.store') }}" method="POST">
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

                    <!-- Dropdown Pilih Supplier -->
                    <div class="flex flex-col mb-4">
                        <label for="supplier_id" class="block text-sm font-semibold">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Jumlah dan Harga Beli -->
                    <div class="flex flex-row mb-4 gap-6">
                        <div class="w-1/2">
                            <label for="jumlah" class="block text-sm font-semibold">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" class="w-full px-4 py-2 border rounded-lg" value="{{ old('jumlah') }}" required>
                        </div>

                        <div class="w-1/2">
                            <label for="harga_beli" class="block text-sm font-semibold">Harga Beli</label>
                            <input type="text" name="harga_beli" id="harga_beli" class="w-full px-4 py-2 border rounded-lg" value="{{ old('harga_beli') }}" required>
                        </div>
                    </div>

                    <!-- Input Tanggal Pembelian -->
                    <div class="flex flex-col mb-4">
                        <label for="tanggal_pembelian" class="block text-sm font-semibold">Tanggal Pembelian</label>
                        <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" class="w-full px-4 py-2 border rounded-lg" value="{{ old('tanggal_pembelian') }}" required>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- AutoNumeric untuk formatting harga beli -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new AutoNumeric('#harga_beli', {
                currencySymbol: 'Rp. ',
                decimalCharacter: ',',
                digitGroupSeparator: '.',
                unformatOnSubmit: true
            });
        });
    </script>
</x-app-layout>
