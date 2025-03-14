<x-app-layout>
    <div class="p-4">
        <div class="w-full p-10 mx-auto">
            <div class="text-gay-900 text-4xl font-semibold mb-3">
                Kategori Obat
            </div>

            <div class="flex flex-row justify-between mb-3">
                <div class="flex flex-row gap-6">
                    {{-- Export Button --}}
                    <button class="px-4 py-2 gap-2 bg-white flex flex-row border rounded-lg justify-center items-center hover:bg-gray-50 transition-all duration-200">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8" />
                        </svg>
                        <p class="font-semibold">Export</p>
                    </button>
                    {{-- Delete Button --}}
                    <button form="deleteFormKategoriObat" type="submit"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 border rounded-lg flex items-center">
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
                    {{-- SEARCH BAR --}}
                    <form id="searchForm" action="{{ route('kategori_obats.index') }}" method="GET" class="relative">
                        <input type="search" name="search" id="search"
                            class="block w-full px-10 py-3 rounded-lg border border-gray-200 placeholder:font-bold"
                            value="" placeholder="Search" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                    </form>
                    {{-- BUTTON TAMBAH --}}
                    <a href="{{ route('kategori_obats.create') }}"
                        class="px-4 py-3 gap-2 bg-[#4268F6] flex flex-row border rounded-lg justify-center items-center">
                        <div class="flex flex-row items-center gap-2">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>
                            <p class="font-semibold text-white">Kategori Baru</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Container untuk memuat partial view tabel Supplier via AJAX -->
            <div id="kategori_obatTableContainer">
                @include('kategori_obat_folder.partials.table')
            </div>
        </div>
    </div>
</x-app-layout>
