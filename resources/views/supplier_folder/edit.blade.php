<x-app-layout>
    <div class="h-full">
        <div class="w-5/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-2 text-gray-800">Edit Data Supplier</h2>

                <!-- Form Edit Obat -->
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST"
                    class="bg-white mb-4 p-8 rounded-lg">
                    @csrf
                    @method('PUT')
                    <div class="mb-8">
                        <label for="nama_supplier" class="block text-base font-semibold">Nama Supplier<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="nama_supplier" id="nama_supplier"
                            class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
                        @if ($errors->has('nama_supplier'))
                            <p class="text-red-500 text-base mt-2">{{ $errors->first('nama_supplier') }}</p>
                        @endif
                    </div>

                    @error('nama_supplier')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-8">
                        <label for="alamat" class="block text-base font-semibold">Alamat<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="alamat" id="alamat"
                            class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('alamat', $supplier->alamat) }}" required>
                    </div>

                    @error('alamat')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-8">
                        <label for="telepon" class="block text-base font-semibold">telepon<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <textarea name="telepon" id="telepon" class="w-full px-4 py-2 border rounded-md border-gray-300">{{ old('telepon', $supplier->telepon) }}</textarea>
                    </div>

                    @error('telepon')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-8">
                        <label for="email" class="block text-base font-semibold">Email<span
                                class="text-pink-500 ml-0.5">*</span></label>
                        <input type="email" name="email" id="email"
                            class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('email', $supplier->email) }}" required>
                    </div>

                    @error('email')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-row gap-6 mb-4">
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
                        <a href="{{ route('suppliers.index') }}">
                            <div class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Cancel</div>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
