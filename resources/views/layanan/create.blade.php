<x-app-layout>
    <div class="h-full relative">
        <div class="w-5/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Tambah Data layanan</h2>
                <!-- Form Tambah layanan -->
                <form action="{{ route('layanan.store') }}" method="POST" class="bg-white mb-4 p-8 rounded-lg">
                    @csrfA

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="nama_layanan" class="block text-sm font-semibold">Nama Pelanggan<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                        <div class="w-1/2">
                            <label for="deskripsi" class="block text-sm font-semibold">deskripsi<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="no_pesanan" id="no_pesanan" value="{{ old('no_pesanan') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>
                    </div>
                    @error('nama_layanan')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror
                    @error('deskripsi')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror

                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/2">
                            <label for="harga" class="block text-sm font-semibold">harga<span class="text-pink-500 ml-0.5">*</span></label>
                            <input type="date" name="harga" id="harga" value="{{ old('harga') }}"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        </div>

                    </div>
                    @error('harga')<div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg"><p class="text-red-700 text-sm">{{ $message }}</p></div>@enderror





                    <div class="flex flex-row gap-4 mb-4">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold flex items-center gap-2">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/></svg>
                            <span>Tambah</span>
                        </button>
                        <a href="{{ route('layanan.index') }}" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold inline-flex items-center justify-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
  // 1. Inisialisasi harga per kilo
  const unitPrice = 15000; // Rp 15.000 / kg

  // 2. Inisialisasi AutoNumeric pada field total_harga
  const totalAN = new AutoNumeric('#total_harga', {
    currencySymbol      : 'Rp. ',
    decimalCharacter    : ',',
    digitGroupSeparator : '.',
    unformatOnSubmit    : true
  });

  // 3. Tangkap elemen input berat
  const beratInput = document.getElementById('berat_pesanan');

  // 4. Fungsi hitung dan set total
  function updateTotalHarga() {
    const berat = parseFloat(beratInput.value) || 0;
    const total = berat * unitPrice;
    totalAN.set(total); // otomatis format
  }

  // 5. Jalankan sekali untuk nilai awal (edit)
  updateTotalHarga();

  // 6. Attach listener untuk setiap perubahan berat
  beratInput.addEventListener('input', updateTotalHarga);
});
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
