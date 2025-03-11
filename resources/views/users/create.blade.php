<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Management') }}
        </h2>
    </x-slot>

    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Tambah Akun Baru</h2>

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
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold">Name</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('name') }}" required>
                    </div>

                    @error('name')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('email') }}" required>
                    </div>

                    @error('email')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold">Password</label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg"
                            required>
                    </div>

                    @error('password')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2 border rounded-lg" required>
                        @if ($errors->has('password'))
                            <div class="mt-1 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative">
                                <p class="text-red-700 text-sm">{{ $errors->first('password') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-semibold">Role</label>
                        <input type="text" name="role" id="role" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('role') }}">
                    </div>

                    @error('role')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <label for="telepon" class="block text-sm font-semibold">Phone</label>
                        <input type="text" name="telepon" id="telepon" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('telepon') }}">
                    </div>

                    @error('telepon')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Tambah Akun</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</x-app-layout>
