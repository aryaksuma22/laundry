{{-- pemesanan/index.blade.php --}}
<x-app-layout>
    <main id="main-content">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="w-full mx-auto">
                <div class="text-gray-900 text-2xl md:text-3xl lg:text-4xl font-semibold mb-6">
                    {{ __('Data Pemesanan') }}
                </div>
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">

                    {{-- Aksi Kiri: Delete Button --}}
                    <div class="flex-shrink-0"> {{-- Cegah tombol delete memakan lebar tidak perlu --}}
                        <button type="button" id="bulkDeleteButton" {{-- Change type to "button" and add the ID --}}
                            class="px-3 py-2 bg-red-500 hover:bg-red-600 transition-colors duration-150 border rounded-lg flex items-center text-white shadow-sm">
                            {{-- SVG Icon (remains the same) --}}
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{-- Text (remains the same) --}}
                            <span class="ml-2 font-semibold text-sm">Delete Selected</span>
                        </button>
                    </div>

                    {{-- Aksi Kanan: Gunakan flex-wrap tapi kontrol spacing dan alignment --}}
                    {{-- Hilangkan flex-col, gunakan flex-row dari awal. justify-end agar rata kanan --}}
                    <div class="flex flex-row items-center justify-start md:justify-end gap-3 w-full md:w-auto">

                        {{-- Tombol Sortir --}}
                        <div class="relative inline-block text-left flex-shrink-0"> {{-- flex-shrink-0 agar tidak mengecil --}}
                            <button id="sortByButtonPemesanan" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                aria-haspopup="true" aria-expanded="true">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span id="sortByButtonPemesananText">Sort By:
                                    {{ ucwords(str_replace('_', ' ', $sortBy)) }}</span>
                            </button>
                            {{-- Popup Sort (tetap sama) --}}
                            <div id="sortByPopupPemesanan"
                                class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                role="menu" aria-orientation="vertical" aria-labelledby="sortByButton">
                                <div class="py-1" role="none">
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionPemesanan"
                                        role="menuitem" data-sortby="tanggal_pesan">Tgl Pesan</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionPemesanan"
                                        role="menuitem" data-sortby="nama_pelanggan">Nama Pelanggan</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionPemesanan"
                                        role="menuitem" data-sortby="no_pesanan">No Pesanan</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionPemesanan"
                                        role="menuitem" data-sortby="status_pesanan">Status Pesanan</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionPemesanan"
                                        role="menuitem" data-sortby="total_harga">Total Harga</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionPemesanan"
                                        role="menuitem" data-sortby="metode_layanan">Metode Layanan</a>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Toggle Order --}}
                        <button id="toggleSortOrderPemesanan"
                            title="Toggle Sort Order ({{ $sortOrder == 'asc' ? 'Ascending' : 'Descending' }})"
                            class="flex-shrink-0 bg-white flex justify-center items-center p-2 rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 text-gray-500 {{ $sortOrder == 'desc' ? '' : 'rotate-180' }}"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M9 5 5 1 1 5m0 6 4 4 4-4" />
                            </svg>
                        </button>

                        {{-- Search Bar: Pastikan form punya relative dan icon absolute --}}
                        {{-- Tambahkan min-w-[...] agar tidak terlalu kecil di layar sempit --}}
                        <form action="{{ route('pemesanan.index') }}" method="GET" id="searchFormPemesanan"
                            class="flex-shrink-0 min-w-[200px] overflow-hidden">
                            <div
                                class="flex items-center border border-gray-300 rounded-md shadow-sm focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                                <!-- inline icon, no absolute -->
                                <span class="flex items-center px-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </span>
                                <!-- input flexes to fill -->
                                <input type="search" name="search" id="search"
                                    class="flex-1 block w-full border-0 pl-3 pr-4 py-2 focus:outline-none focus:ring-0 sm:text-sm rounded-r-md"
                                    value="{{ $search }}" placeholder="Cari..." />
                            </div>
                        </form>


                        {{-- Button Tambah --}}
                        <a href="{{ route('pemesanan.create') }}"
                            class="flex-shrink-0 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>
                            Pemesanan Baru
                        </a>

                        {{-- Dropdown Per Page --}}
                        <div
                            class="flex-shrink-0 flex items-center bg-white border border-gray-300 rounded-md shadow-sm px-2 py-1 text-sm">
                            <label for="perPage" class="mr-2 text-gray-700">Show:</label>
                            <select name="perPage" id="perPagePemesanan"
                                class="border-none focus:ring-0 focus:outline-none text-sm py-1 bg-gray-100 rounded-md">
                                <option value="5" {{ request('perPage', $perPage) == 5 ? 'selected' : '' }}>5
                                </option>
                                <option value="10" {{ request('perPage', $perPage) == 10 ? 'selected' : '' }}>
                                    10</option>
                                <option value="25" {{ request('perPage', $perPage) == 25 ? 'selected' : '' }}>
                                    25</option>
                                <option value="50" {{ request('perPage', $perPage) == 50 ? 'selected' : '' }}>
                                    50</option>
                            </select>
                            <span class="ml-1 text-gray-700">entries</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs JS state (tetap sama) -->
                <input type="hidden" id="sort-byPemesanan" value="{{ $sortBy }}">
                <input type="hidden" id="sort-order" value="{{ $sortOrder }}">

                <!-- Container tabel (tetap sama) -->
                <div id="pemesananTableContainer"
                    class="relative bg-white overflow-x-auto shadow-sm rounded-lg border min-h-[250px]">
                    <div class="text-center p-10 text-gray-500">Memuat data pemesanan...</div>
                </div>

            </div>
        </div>
    </main>

    {{-- SweetAlert (tetap sama) --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK',
                timer: 2500,
                timerProgressBar: true
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</x-app-layout>
