<x-app-layout>
    <main id="main-content">
        <div class="p-4">
            <div class="w-full p-10 mx-auto">
                <div class="text-gray-900 text-4xl font-semibold mb-5">
                    {{ __('Data Penjualan Obat') }}
                </div>

                <div class="flex flex-row justify-between mb-3">
                    <div class="flex flex-row gap-4">
                        {{-- Export Button --}}
                        <button
                            class="px-4 py-2 gap-2 bg-white flex flex-row border rounded-lg justify-center items-center hover:bg-gray-50 transition-all duration-200">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8" />
                            </svg>
                            <p class="font-semibold">Export</p>
                        </button>
                        {{-- Delete Button --}}
                        <button form="deleteFormPenjualanObat" type="submit"
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
                            {{-- Sorting Button --}}
                            <button id="toggleSortOrder"
                                class="bg-white flex justify-center items-center px-3 py-2 rounded-lg border hover:bg-gray-50 transition-all duration-100">
                                <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M8 20V10m0 10-3-3m3 3 3-3m5-13v10m0-10 3 3m-3-3-3 3" />
                                </svg>
                            </button>
                            <!-- Button Sort By -->
                            <button id="sortByButton"
                                class="flex flex-row gap-2 px-4 py-3 bg-white border rounded-lg justify-center items-center relative hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                        d="M5 7h14M5 12h14M5 17h10" />
                                </svg>
                                <p class="font-semibold text-black">Sort By</p>

                                <!-- Dropdown Sort By -->
                                <div id="sortByPopup"
                                    class="hidden absolute w-[7.5rem] bg-white shadow-sm rounded-lg border top-[3.4rem] right-0 divide-y divide-gray-200 overflow-hidden">
                                    <div class="py-2 px-4 cursor-pointer sort-option hover:bg-gray-50"
                                        data-sortby="id">ID</div>
                                    <div class="py-2 px-4 cursor-pointer sort-option hover:bg-gray-50"
                                        data-sortby="nama_obat">Obat</div>
                                    <div class="py-2 px-4 cursor-pointer sort-option hover:bg-gray-50"
                                        data-sortby="nama_supplier">
                                        Supplier</div>
                                    <div class="py-2 px-4 cursor-pointer sort-option hover:bg-gray-50"
                                        data-sortby="jumlah">Jumlah</div>
                                    <div class="py-2 px-4 cursor-pointer sort-option hover:bg-gray-50"
                                        data-sortby="harga_beli">Harga
                                        Beli
                                    </div>
                                    <div class="py-2 px-4 cursor-pointer sort-option hover:bg-gray-50"
                                        data-sortby="total_harga">Total
                                        Harga</div>
                                    <div class="py-2 px-4 cursor-pointer sort-option hover:bg-gray-50"
                                        data-sortby="tanggal_pemmbelian">
                                        Tanggal Pembelian</div>
                                </div>
                            </button>
                        </div>
                        {{-- SEARCH BAR --}}
                        <form id="searchForm" action="{{ route('penjualan_obats.index') }}" method="GET"
                            class="relative">
                            <input type="search" name="search" id="search"
                                class="block w-full px-10 py-3 rounded-lg border border-gray-200 placeholder:font-bold"
                                value="{{ $search }}" placeholder="Search" />
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                        </form>
                        {{-- BUTTON TAMBAH --}}
                        <a href="{{ route('penjualan_obats.create') }}"
                            class="px-4 py-3 gap-2 bg-[#4268F6] hover:bg-[#3a5ddc] flex flex-row border rounded-lg justify-center items-center transition-all duration-100">
                            <div class="flex flex-row items-center gap-2">
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 12h14m-7 7V5" />
                                </svg>
                                <p class="font-semibold text-white">Penjualan Baru</p>
                            </div>
                        </a>
                        <!-- Dropdown untuk memilih entries per halaman -->
                        <div class="flex items-center border rounded-lg bg-white px-6 py-1">
                            <form action="{{ route('penjualan_obats.index') }}" method="GET" id="perPageForm">
                                <label for="perPage" class="mr-2 text-sm">Show</label>
                                <select name="perPage" id="perPage" class="border border-gray-200 rounded">
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

                <!-- Container untuk memuat partial view tabel Penjualan Obat via AJAX -->
                <div id="penjualan_obatTableContainer">
                    @include('penjualan_obat_folder.partials.table')
                </div>
            </div>
        </div>
    </main>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
</x-app-layout>
