<x-app-layout>
    <div class="h-full">
        <div class="w-5/6 p-12 mx-auto">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold mb-2 text-gray-800">Tambah Akun Baru</h2>

                {{-- <!-- Tampilkan pesan error global -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-red-500">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <!-- Form untuk menambah akun baru -->
                <form action="{{ route('users.store') }}" method="POST" class="bg-white mb-4 p-8 rounded-lg">
                    @csrf

                    <div class="mb-8">
                        <label for="name" class="block text-sm font-semibold">Name<span
                            class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('name') }}" required>
                    </div>

                    @error('name')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-8">
                        <label for="email" class="block text-sm font-semibold">Email<span
                            class="text-pink-500 ml-0.5">*</span></label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('email') }}" required>
                    </div>

                    @error('email')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-8">
                        <label for="password" class="block text-sm font-semibold">Password<span
                            class="text-pink-500 ml-0.5">*</span></label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-md border-gray-300"
                            required>
                    </div>

                    @error('password')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-8">
                        <label for="password_confirmation" class="block text-sm font-semibold">Confirm Password<span
                            class="text-pink-500 ml-0.5">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 border rounded-md border-gray-300" required>
                        @if ($errors->has('password'))
                            <div class="mt-1 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative">
                                <p class="text-red-700 text-sm">{{ $errors->first('password') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-8">
                        <label for="role" class="block text-sm font-semibold">Role<span
                            class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="role" id="role" class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('role') }}">
                    </div>

                    @error('role')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-8">
                        <label for="telepon" class="block text-sm font-semibold">Phone<span
                            class="text-pink-500 ml-0.5">*</span></label>
                        <input type="text" name="telepon" id="telepon" class="w-full px-4 py-2 border rounded-md border-gray-300"
                            value="{{ old('telepon') }}">
                    </div>

                    @error('telepon')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror
                    <div class="flex flex-row gap-4 mb-4">
                        <div class="">
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold flex flex-row gap-2 items-center">
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z"
                                        clip-rule="evenodd" />
                                </svg>
    
                                <p>Tambah Akun</p>
                            </button>
                        </div>
                        <a href="{{ route('users.index') }}">
                            <div class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold">Cancel
                            </div>
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

</x-app-layout>
