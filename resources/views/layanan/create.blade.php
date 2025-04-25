<x-app-layout>
    <div class="h-full relative">
        <div class="w-5/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">Tambah Data Layanan</h2>
                <form action="{{ route('layanans.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow">
                    @csrf

                    <div class="mb-6">
                        <label for="nama_layanan" class="block text-sm font-semibold">Nama Layanan<span class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="nama_layanan" id="nama_layanan"
                            value="{{ old('nama_layanan') }}"
                            class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        @error('nama_layanan')
                            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-semibold">Deskripsi<span class="text-pink-500 ml-0.5">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            class="w-full px-4 py-2 border rounded-md border-gray-300" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="harga" class="block text-sm font-semibold">Harga (Rp)<span class="text-pink-500 ml-0.5">*</span></label>
                        <input type="number" name="harga" id="harga"
                            value="{{ old('harga') }}"
                            class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        @error('harga')
                            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                            Tambah
                        </button>
                        <a href="{{ route('layanans.index') }}" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
