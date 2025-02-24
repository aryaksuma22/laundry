<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-5">Tambah Data Supplier</h2>

                {{-- Form Tambah Supplier --}}
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="nama_supplier" class="block text-base font-semibold">Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="nama_supplier"
                            class="w-full px-4 py-2 border rounded-lg" value="{{ old('nama_supplier') }}" required>
                        @if ($errors->has('nama_supplier'))
                            <p class="text-red-500 text-base mt-2">{{ $errors->first('nama_supplier') }}</p>
                        @endif
                    </div>
                    <div class="mb-4">
                        <label for="alamat" class="block text-base font-semibold">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('alamat') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="telepon" class="block text-base font-semibold">Telepon</label>
                        <input type="text" name="telepon" id="telepon" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('telepon') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-base font-semibold">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('email') }}" required>
                    </div>

                    <div class="">
                        <button type="submit"
                            class="px-6 py-2 bg-[#4268F6] text-white rounded-lg hover:bg-[#3a5cd8] font-semibold">Tambah</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
