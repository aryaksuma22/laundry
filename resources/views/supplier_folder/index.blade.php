<x-app-layout>
    <div class="p-4">
        <div class="w-full p-10 mx-auto">
            <div class="text-gray-900 text-4xl font-semibold mb-5">
                {{ __('Data Supplier') }}
            </div>

            <div class="flex flex-row justify-between mb-3">
                <div class="flex flex-row gap-4">
                    {{-- Export Button --}}
                    <button class="px-4 py-2 gap-2 bg-white flex flex-row border rounded-lg justify-center items-center">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                             viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12 4 4m-4-4L8 8" />
                        </svg>
                        <p class="font-semibold">Export</p>
                    </button>
                    {{-- Delete Button --}}
                    <button form="deleteForm" type="submit"
                            class="px-4 py-2 bg-red-500 border rounded-lg flex items-center">
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
                                class="bg-white flex justify-center items-center px-3 py-2 rounded-lg border hover:bg-gray-50">
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
                                 class="hidden absolute w-[10rem] bg-white shadow-sm rounded-xl border top-[3.4rem] right-0 divide-y divide-gray-200">
                                <div class="py-2 px-4 cursor-pointer sort-option" data-sortby="id">ID</div>
                                <div class="py-2 px-4 cursor-pointer sort-option" data-sortby="nama_supplier">Nama Supplier</div>
                            </div>
                        </button>
                    </div>
                    {{-- SEARCH BAR --}}
                    <form action="{{ route('suppliers.index') }}" method="GET" class="relative">
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
                    <a href="{{ route('suppliers.create') }}"
                       class="px-4 py-3 gap-2 bg-[#4268F6] flex flex-row border rounded-lg justify-center items-center">
                        <div class="flex flex-row items-center gap-2">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>
                            <p class="font-semibold text-white">Supplier Baru</p>
                        </div>
                    </a>
                    <!-- Dropdown untuk memilih entries per halaman -->
                    <div class="flex items-center border rounded-lg bg-white px-6 py-1">
                        <form action="{{ route('suppliers.index') }}" method="GET" id="perPageForm">
                            <label for="perPage" class="mr-2 text-sm">Show</label>
                            <select name="perPage" id="perPage"
                                    onchange="document.getElementById('perPageForm').submit()"
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

            {{-- Tabel Data Supplier --}}
            <form id="deleteForm" action="{{ route('suppliers.destroy', ['supplier' => 0]) }}" method="POST">
                @csrf
                @method('DELETE')
                <table class="min-w-full bg-white overflow-hidden rounded-xl shadow-sm mb-5">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">
                                <input type="checkbox" class="form-checkbox rounded-[4px]" id="checkbox-all" />
                            </th>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Nama Supplier</th>
                            <th class="px-4 py-2 text-left">Alamat</th>
                            <th class="px-4 py-2 text-left">Telepon</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr class="border">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="suppliers[]" value="{{ $supplier->id }}"
                                           class="form-checkbox rounded-[5px] checkbox-row" />
                                </td>
                                <td class="px-4 py-3">{{ $supplier->id }}</td>
                                <td class="px-4 py-3">{{ $supplier->nama_supplier }}</td>
                                <td class="px-4 py-3">{{ $supplier->alamat }}</td>
                                <td class="px-4 py-3">{{ $supplier->telepon }}</td>
                                <td class="px-4 py-3">{{ $supplier->email }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z"
                                                  clip-rule="evenodd" />
                                            <path fill-rule="evenodd"
                                                  d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>

            <!-- Pagination links -->
            <div class="mt-4">
                {{ $suppliers->appends(['search' => request('search'), 'perPage' => request('perPage')])->links() }}
            </div>
        </div>
    </div>

    <script>
        // Checkbox header: cek/uncek semua
        $('#checkbox-all').on('change', function() {
            $('.checkbox-row').prop('checked', $(this).prop('checked'));
        });

        // Update checkbox header jika semua checkbox tercentang
        $('.checkbox-row').on('change', function() {
            $('#checkbox-all').prop('checked', $('.checkbox-row').length === $('.checkbox-row:checked').length);
        });

        $(document).ready(function() {
            var sortOrder = '{{ $sortOrder }}';

            // Toggle sort order saat button diklik
            $('#toggleSortOrder').on('click', function() {
                sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
                $(this).toggleClass('bg-gray-200');
                updateUrl(null, sortOrder);
            });

            // Saat opsi Sort By diklik
            $('.sort-option').on('click', function() {
                var sortBy = $(this).data('sortby');
                updateUrl(sortBy, sortOrder);
            });

            // Tampilkan/Sembunyikan popup Sort By
            $('#sortByButton').on('click', function(event) {
                event.stopPropagation();
                $('#sortByPopup').stop(true, true).fadeToggle(200);
            });

            // Sembunyikan popup jika klik di luar
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#sortByButton').length && !$(event.target).closest('#sortByPopup').length) {
                    $('#sortByPopup').fadeOut(200);
                }
            });

            // Fungsi untuk update URL dengan parameter baru
            function updateUrl(sortBy = null, sortOrder = null) {
                var currentUrl = window.location.href;
                if (sortBy) {
                    currentUrl = updateQueryStringParameter(currentUrl, 'sortBy', sortBy);
                }
                if (sortOrder) {
                    currentUrl = updateQueryStringParameter(currentUrl, 'sortOrder', sortOrder);
                }
                window.location.href = currentUrl;
            }

            // Fungsi untuk update query string dalam URL
            function updateQueryStringParameter(url, param, value) {
                var re = new RegExp("([?&])" + param + "=.*?(&|$)", "i");
                var separator = url.indexOf('?') !== -1 ? "&" : "?";
                if (url.match(re)) {
                    return url.replace(re, '$1' + param + "=" + value + '$2');
                } else {
                    return url + separator + param + "=" + value;
                }
            }
        });

        $(document).ready(function() {
            // Menangani perubahan entri per halaman
            $('#perPage').on('change', function() {
                var perPage = $(this).val();
                var currentUrl = window.location.href;
                currentUrl = updateQueryStringParameter(currentUrl, 'perPage', perPage);
                window.location.href = currentUrl;
            });

            function updateQueryStringParameter(url, param, value) {
                var re = new RegExp("([?&])" + param + "=.*?(&|$)", "i");
                var separator = url.indexOf('?') !== -1 ? "&" : "?";
                if (url.match(re)) {
                    return url.replace(re, '$1' + param + "=" + value + '$2');
                } else {
                    return url + separator + param + "=" + value;
                }
            }
        });
    </script>
</x-app-layout>
