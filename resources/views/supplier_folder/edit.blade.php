<x-app-layout>
    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Edit Data Supplier</h2>

                <!-- Form Edit Obat -->
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="nama_supplier" class="block text-base font-semibold">Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="nama_supplier"
                            class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
                        @if ($errors->has('nama_supplier'))
                            <p class="text-red-500 text-base mt-2">{{ $errors->first('nama_supplier') }}</p>
                        @endif
                    </div>
                    <div class="mb-4">
                        <label for="alamat" class="block text-base font-semibold">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('alamat', $supplier->alamat) }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="telepon" class="block text-base font-semibold">telepon</label>
                        <textarea name="telepon" id="telepon" class="w-full px-4 py-2 border rounded-lg">{{ old('telepon', $supplier->telepon) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-base font-semibold">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('email', $supplier->email) }}" required>
                    </div>
                    <div class="">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
