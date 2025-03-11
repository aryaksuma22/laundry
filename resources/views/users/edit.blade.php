<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Management') }}
        </h2>
    </x-slot>

    <div class="p-4">
        <div class="w-1/2 p-10">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold mb-6">Edit User</h2>

                <!-- Form Edit -->
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Menandakan bahwa ini adalah update, bukan store -->

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold">Name</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('name', $user->name) }}" required>
                    </div>

                    @error('name')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('email', $user->email) }}" required>
                    </div>

                    @error('email')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-semibold">Role</label>
                        <input type="text" name="role" id="role" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('role', $user->role) }}">
                    </div>

                    @error('role')
                        <div class="mb-4 bg-red-100 border-red-400 px-4 py-3 rounded-lg relative ">
                            <p class="text-red-700 text-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="mb-4">
                        <label for="telepon" class="block text-sm font-semibold">Phone</label>
                        <input type="text" name="telepon" id="telepon" class="w-full px-4 py-2 border rounded-lg"
                            value="{{ old('telepon', $user->telepon) }}">
                    </div>

                    @error('telepon')
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
