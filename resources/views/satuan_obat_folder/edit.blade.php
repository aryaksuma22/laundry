<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Edit Data Obat</h2>

                <!-- Form Edit Obat -->
                <form action="{{ route('satuan_obats.update', $satuan_obat->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Menandakan bahwa ini adalah update, bukan store -->
                    <div class="mb-3">
                        <label for="nama_satuan" class="block text-lg font-semibold mb-2">Nama Satuan</label>
                        <input type="text" name="nama_satuan" id="nama_satuan"
                            class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('nama_satuan', $satuan_obat->nama_satuan) }}" required>
                    </div>

                    @error('nama_satuan')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
