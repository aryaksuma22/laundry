<x-app-layout>
    <div class="h-full">
        <div class="w-4/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Tambah Data Penjualan Obat</h2>

                <!-- Form Tambah Penjualan Obat -->
                <form action="{{ route('penjualan_obats.store') }}" method="POST" class="bg-white mb-4 p-8 rounded-lg">
                    @csrf

                    <!-- Dropdown Pilih Obat -->
                    <div class="flex flex-col mb-8">
                        <label for="obat_id" class="block text-sm font-semibold">Nama Obat<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <select name="obat_id" id="obat_id" class="w-full px-4 py-2 border rounded-md border-gray-300"
                            required>
                            <option value="">Pilih Obat</option>
                            @foreach ($obats as $obat)
                                <option value="{{ $obat->id }}" {{ old('obat_id') == $obat->id ? 'selected' : '' }}>
                                    {{ $obat->nama_obat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @error('obat_id')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <!-- Input Jumlah dan Harga Jual -->
                    <div class="flex flex-row gap-6 mb-8">
                        <div class="w-1/6">
                            <label for="jumlah" class="block text-sm font-semibold">Jumlah<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="number" name="jumlah" id="jumlah"
                                class="w-full px-4 py-2 border rounded-md border-gray-300" value="{{ old('jumlah') }}"
                                required>
                        </div>

                        <div class="w-5/6">
                            <label for="harga_jual" class="block text-sm font-semibold">Harga Jual<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="harga_jual" id="harga_jual"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('harga_jual') }}" required>
                        </div>
                    </div>

                    @error('jumlah')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    @error('harga_jual')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row gap-6 mb-4">
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
                        <a href="{{ route('penjualan_obats.index') }}">
                            <div class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Cancel</div>
                        </a>
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
