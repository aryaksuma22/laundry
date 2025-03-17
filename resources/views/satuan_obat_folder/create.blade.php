<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Tambah Satuan</h2>

                {{-- Form Tambah Kateogri --}}
                <form action="{{ route('satuan_obats.store') }}" method="POST">
                    @csrf
                    <div class="flex flex-col gap-4 mb-4">
                        <label for="nama_satuan" class="block text-lg font-semibold">Nama Satuan</label>
                        <input type="text" name="nama_satuan" id="nama_satuan"
                            class="w-full px-4 py-2 border rounded-lg" value="{{ old('nama_satuan') }}" required>
                    </div>

                    @error('nama_satuan')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row gap-6 mb-4">
                        <div class="">
                            <button type="submit"
                                class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Tambah</button>
                        </div>
                        <a href="{{ route('satuan_obats.index') }}">
                            <div class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Cancel</div>
                        </a>
                    </div>
            </div>

            </form>
        </div>
    </div>
</x-app-layout>
