<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Tambah Kategori</h2>

                {{-- Form Tambah Kateogri --}}
                <form action="{{ route('kategori_obats.store') }}" method="POST">
                    @csrf
                    <div class="flex flex-col gap-4 mb-4">
                        <label for="nama_kategori" class="block text-lg font-semibold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori"
                            class="w-full px-4 py-2 border rounded-lg" value="{{ old('nama_kategori') }}" required>
                    </div>

                    @error('nama_kategori')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <button type="submit"
                        class="px-6 py-2 bg-[#4268F6] text-white rounded-lg hover:bg-[#3859d2]">Tambah</button>
            </div>
            </form>
        </div>
    </div>
</x-app-layout>
