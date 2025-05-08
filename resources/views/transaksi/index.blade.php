{{-- transaksi/index.blade.php --}}
<x-app-layout>
    <main id="main-content">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="w-full mx-auto">
                {{-- Page Title --}}
                <div class="text-gray-900 text-2xl md:text-3xl lg:text-4xl font-semibold mb-6">
                    {{ __('Data Transaksi') }} {{-- Ganti Judul --}}
                </div>

                {{-- Main Action Row --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                    {{-- Delete Button (jika diperlukan untuk transaksi) --}}
                    <div class="flex-shrink-0">
                        <button type="button" id="bulkDeleteButton" {{-- ID bisa sama jika JS menghandle konteks --}}
                            class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm flex items-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                    clip-rule="evenodd" />
                            </svg><span class="ml-2 font-semibold text-sm">Delete Selected</span>
                        </button>
                    </div>
                    <div
                        class="flex flex-row flex-wrap items-center justify-start md:justify-end gap-3 w-full md:w-auto">
                        {{-- Sort --}}
                        <div class="relative inline-block text-left flex-shrink-0">
                            {{-- Ganti ID --}}
                            <button id="sortByButtonTransaksi" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{-- Ganti ID & Teks Awal --}}
                                <span id="sortByButtonTransaksiText">Sort By:
                                    {{ ucwords(str_replace('_', ' ', $sortBy ?? 'created_at')) }}
                                </span>
                            </button>
                            {{-- Ganti ID & Options --}}
                            <div id="sortByPopupTransaksi"
                                class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                <div class="py-1">
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionTransaksi"
                                        data-sortby="created_at">Tgl Transaksi</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionTransaksi"
                                        data-sortby="no_invoice">No Invoice</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionTransaksi"
                                        data-sortby="pemesanan.no_pesanan">No Pesanan</a> {{-- Contoh sort by relasi --}}
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionTransaksi"
                                        data-sortby="jumlah_dibayar">Jumlah Dibayar</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionTransaksi"
                                        data-sortby="status_pembayaran">Status Pembayaran</a>
                                    <a href="#"
                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 sort-optionTransaksi"
                                        data-sortby="metode_pembayaran">Metode Pembayaran</a>
                                </div>
                            </div>
                        </div>
                        {{-- Sort Order --}}
                        {{-- Ganti ID --}}
                        <button id="toggleSortOrderTransaksi" title="Toggle Sort Order"
                            class="flex-shrink-0 bg-white flex justify-center items-center p-2 rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 focus:ring-indigo-500">
                            <svg class="w-5 h-5 text-gray-500 {{ ($sortOrder ?? 'desc') == 'desc' ? '' : 'rotate-180' }}"
                                fill="none" viewBox="0 0 10 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M9 5 5 1 1 5m0 6 4 4 4-4" />
                            </svg>
                        </button>
                        {{-- Search (ID bisa sama jika JS menghandle konteks) --}}
                        <form id="searchFormTransaksi" class="flex-shrink-0 min-w-[200px] overflow-hidden">
                            <div
                                class="flex items-center border border-gray-300 rounded-md shadow-sm focus-within:ring-indigo-500">
                                <span class="flex items-center px-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </span>
                                <input type="search" name="search" id="search" {{-- ID bisa sama --}}
                                    class="flex-1 block w-full border-0 pl-0 pr-4 py-2 focus:ring-0 sm:text-sm rounded-r-md"
                                    value="{{ $search ?? '' }}" placeholder="Cari Transaksi..." />
                            </div>
                        </form>
                        {{-- Add (Tombol "Transaksi Baru" mungkin tidak selalu relevan di sini, bisa jadi dari halaman Pemesanan) --}}
                        <a href="{{ route('transaksis.create') }}"
                            class="flex-shrink-0 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Transaksi Baru
                        </a>
                        {{-- Per Page --}}
                        {{-- Ganti ID --}}
                        <div
                            class="flex-shrink-0 flex items-center bg-white border border-gray-300 rounded-md shadow-sm px-2 py-1 text-sm">
                            <label for="perPageTransaksi" class="mr-2 text-gray-700">Show:</label>
                            <select name="perPage" id="perPageTransaksi"
                                class="border-none focus:ring-0 text-sm py-1 bg-gray-100 rounded-md">
                                <option value="5" {{ ($perPage ?? 10) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                            </select><span class="ml-1 text-gray-700">entries</span>
                        </div>
                    </div>
                </div>

                {{-- === FILTER ROW === --}}
                <div
                    class="flex flex-col sm:flex-row items-center flex-wrap gap-3 mb-4 p-4 bg-gray-50 rounded-lg border">
                    <span class="font-semibold text-gray-700 flex-shrink-0 mr-2">Filter:</span>

                    {{-- Filter by Status Pembayaran --}}
                    <div class="flex-grow w-full sm:w-auto">
                        {{-- Ganti ID & name --}}
                        <label for="filter_status_pembayaran" class="sr-only">Filter Status Pembayaran</label>
                        <select name="filter_status_pembayaran" id="filter_status_pembayaran"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200 text-sm">
                            <option value="">-- Semua Status Bayar --</option>
                            @foreach ($filterData['statusOptions'] ?? [] as $status)
                                <option value="{{ $status }}"
                                    {{ ($filterStatusPembayaran ?? '') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter by Metode Pembayaran --}}
                    <div class="flex-grow w-full sm:w-auto">
                        {{-- Ganti ID & name --}}
                        <label for="filter_metode_pembayaran" class="sr-only">Filter Metode Pembayaran</label>
                        <select name="filter_metode_pembayaran" id="filter_metode_pembayaran"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200 text-sm">
                            <option value="">-- Semua Metode Bayar --</option>
                            @foreach ($filterData['metodeOptions'] ?? [] as $metode)
                                <option value="{{ $metode }}"
                                    {{ ($filterMetodePembayaran ?? '') == $metode ? 'selected' : '' }}>
                                    {{ $metode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Anda bisa tambahkan filter lain, misal rentang tanggal --}}

                    {{-- RESET FILTER BUTTON (ID bisa sama) --}}
                    <div class="flex-shrink-0">
                        <button type="button" id="resetFiltersButton" title="Reset Semua Filter"
                            class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md text-sm shadow-sm flex items-center transition-colors duration-150">
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
                {{-- === END FILTER ROW === --}}


                <!-- Hidden inputs JS state (jika diperlukan, atau JS handle langsung) -->
                {{-- <input type="hidden" id="sort-byTransaksi" value="{{ $sortBy ?? 'created_at' }}">
                <input type="hidden" id="sort-orderTransaksi" value="{{ $sortOrder ?? 'desc' }}"> --}}

                <!-- Table Container -->
                {{-- Ganti ID --}}
                <div id="transaksiTableContainer"
                    class="relative bg-white overflow-x-auto shadow-sm rounded-lg border min-h-[250px]">
                    {{-- Include initial table data from controller --}}
                    {{-- Ganti nama variabel dan path include --}}
                    @include('transaksi.partials.table', ['transaksis' => $transaksis])
                </div>

            </div>
        </div>
    </main>

    {{-- SweetAlert (sama seperti di pemesanan) --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
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
