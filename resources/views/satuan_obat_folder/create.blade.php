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
                        @if ($errors->has('nama_satuan'))
                            <p class="text-red-500 text-sm mt-1">{{ $errors->first('nama_satuan') }}</p>
                        @endif
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-[#4268F6] text-white rounded-lg hover:bg-[#3859d2]">Tambah</button>
            </div>

            </form>
        </div>
    </div>
</x-app-layout>
