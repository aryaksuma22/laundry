<x-app-layout>
    <main id="main-content">
        <div class="p-4">
            <div class="w-full p-10 mx-auto">
                <div class="text-gray-900 text-4xl font-semibold mb-5">
                    {{ __('Data Riwayat') }}
                </div>

                <div class="flex flex-row justify-between mb-3">
                    <div class="flex flex-row gap-4">
                        {{-- Delete Button --}}
                        <button form="deleteFormRiwayat" type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 transition-all duration-100 border rounded-lg flex items-center">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-2 text-white font-semibold">Delete</span>
                        </button>
                    </div>

                    <div class="flex flex-row space-x-4 items-center">
                        <div class="flex flex-row gap-2">

                        </div>
                        {{-- Search Bar --}}
                        <form action="{{ route('riwayat.index') }}" method="GET" id="searchForm"
                            class="relative">
                            <input type="search" name="search" id="search"
                                class="block w-full px-10 py-3 rounded-lg border border-gray-200 placeholder:font-bold"
                                value="{{ $search }}" placeholder="Search" />
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                        </form>
                        {{-- Button Tambah --}}
                        <a href="{{ route('riwayat.create') }}"
                            class="px-4 py-3 gap-2 bg-[#4268F6] hover:bg-[#3a5ddc] transition-all duration-100 flex flex-row border rounded-lg justify-center items-center">
                            <div class="flex flex-row items-center gap-2">
                                <svg class="w-6 h-6 text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 12h14m-7 7V5" />
                                </svg>
                                <p class="font-semibold text-white">Riwayat Baru</p>
                            </div>
                        </a>
                        <!-- Dropdown Per Page -->
                        <div class="flex items-center border rounded-lg bg-white px-6 py-1">
                            <form action="{{ route('riwayat.index') }}" method="GET" id="perPageForm">
                                <label for="perPage" class="mr-2 text-sm">Show</label>
                                <select name="perPage" id="perPage"
                                    class="border border-gray-200 rounded">
                                    <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                </select>
                                <span class="ml-1 text-sm">entries</span>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs untuk menyimpan nilai sort-by dan sort-order -->
                <input type="hidden" id="sort-by" value="{{ $sortBy }}">
                <input type="hidden" id="sort-order" value="{{ $sortOrder }}">

                <!-- Container untuk memuat partial view tabel riwayat via AJAX -->
                <div id="riwayatableContainer" class="">
                    @include('riwayat.partials.table')
                </div>
            </div>
        </div>
    </main>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</x-app-layout>
