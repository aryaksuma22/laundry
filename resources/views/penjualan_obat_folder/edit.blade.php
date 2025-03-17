<x-app-layout>
    <div class="h-full">
        <div class="w-4/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Edit Data Penjualan Obat</h2>

                <!-- Form Edit Penjualan Obat -->
                <form action="{{ route('penjualan_obats.update', $penjualan_obat->id) }}" method="POST"
                    class="bg-white mb-4 p-8 rounded-lg">
                    @csrf
                    @method('PUT')

                    <!-- Dropdown Obat -->
                    <div class="flex flex-col mb-8">
                        <label for="obat_id" class="block text-sm font-semibold">Nama Obat<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <select name="obat_id" id="obat_id" class="w-full px-4 py-2 border rounded-md border-gray-300"
                            required>
                            @foreach ($obats as $obat)
                                <option value="{{ $obat->id }}"
                                    {{ $penjualan_obat->obat_id == $obat->id ? 'selected' : '' }}>
                                    {{ $obat->nama_obat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah dan Harga Jual -->
                    <div class="flex flex-row mb-8 gap-6">
                        <div class="w-1/6">
                            <label for="jumlah" class="block text-sm font-semibold">Jumlah<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="number" name="jumlah" id="jumlah"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('jumlah', $penjualan_obat->jumlah) }}" required>
                        </div>

                        <div class="w-5/6">
                            <label for="harga_jual" class="block text-sm font-semibold">Harga Jual<span
                                    class="text-pink-500 ml-0.5">*</span></label>
                            <input type="text" name="harga_jual" id="harga_jual"
                                class="w-full px-4 py-2 border rounded-md border-gray-300"
                                value="{{ old('harga_jual', $penjualan_obat->harga_jual) }}" required>
                        </div>
                    </div>

                    <div class="flex flex-row gap-6 mb-4">
                        <div class="">
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold flex flex-row gap-2 items-center justify-center">
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>Edit</p>
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

    <!-- AutoNumeric untuk formatting harga jual -->
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
