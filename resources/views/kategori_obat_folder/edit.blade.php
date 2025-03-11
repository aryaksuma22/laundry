<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Edit Data Obat</h2>

                <!-- Form Edit Obat -->
                <form action="{{ route('kategori_obats.update', $kategori_obat->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Menandakan bahwa ini adalah update, bukan store -->
                    <div class="mb-3">
                        <label for="nama_kategori" class="block text-lg font-semibold mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori"
                            class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('nama_kategori', $kategori_obat->nama_kategori) }}" required>
                    </div>

                    @error('nama_kategori')
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
