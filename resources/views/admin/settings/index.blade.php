<x-app-layout>
    <main id="main-content">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto">
                <div class="text-gray-900 text-2xl md:text-3xl lg:text-4xl font-semibold mb-6">
                    {{ __('Pengaturan Aplikasi') }}
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Oops! Ada kesalahan:</strong>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('settings.update') }}" method="POST"
                    class="bg-white shadow-md rounded-lg p-6 md:p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    @foreach ($settings as $group => $items)
                        <fieldset class="border rounded-md p-4 shadow-sm">
                            <legend class="text-xl font-semibold text-gray-700 px-2 mb-4">{{ $group }}</legend>
                            <div class="space-y-6">
                                @foreach ($items as $setting)
                                    <div>
                                        <label for="{{ $setting->key }}"
                                            class="block text-sm font-medium text-gray-700">{{ $setting->label }}</label>
                                        @if ($setting->type === 'textarea')
                                            <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="3"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old($setting->key, $setting->value) }}</textarea>
                                        @elseif($setting->type === 'boolean' || $setting->type === 'checkbox')
                                            <div class="mt-1">
                                                <input type="checkbox" name="{{ $setting->key }}"
                                                    id="{{ $setting->key }}"
                                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                    {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                                            </div>
                                        @else
                                            {{-- text, number, dll --}}
                                            <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                                name="{{ $setting->key }}" id="{{ $setting->key }}"
                                                value="{{ old($setting->key, $setting->value) }}"
                                                @if ($setting->type === 'number') step="any" @endif
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @endif

                                        @if ($setting->description)
                                            <p class="mt-2 text-xs text-gray-500">{{ $setting->description }}</p>
                                        @endif
                                        @error($setting->key)
                                            <p class="mt-1 text-red-600 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach

                    <div class="flex justify-end mt-8 pt-6 border-t">
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-semibold">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-app-layout>
