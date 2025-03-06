<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Management') }}
        </h2>
    </x-slot>

    <main id="main-content" class="p-4">
        <div class="w-full p-10 mx-auto">
            <div class="text-gray-900 text-4xl font-semibold mb-5">
                {{ __('Account Management Menu') }}
            </div>

            <div class="flex flex-row justify-between mb-5">
                <div class="flex flex-row gap-4">
                    <div class="px-4 py-2 gap-2 bg-white flex flex-row border rounded-lg justify-center items-center">
                        <!-- Icon Export -->
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2M12 4v12m0-12l4 4m-4-4L8 8" />
                        </svg>
                        <p class="font-semibold">Export</p>
                    </div>

                    <!-- Tombol Delete  -->
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
                        {{-- Icon Sort Ascending - Descending --}}
                        <button class="bg-white flex justify-center items-center px-3 py-2 rounded-lg border hover:bg-gray-50 cursor-pointer"
                            id="toggleSortOrder">
                            <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M8 20V10m0 10l-3-3m3 3l3-3m5-13v10m0-10l3 3m-3-3l-3 3" />
                            </svg>
                        </button>
                        <!-- Icon Sort By -->
                        <button class="flex flex-row gap-2 px-4 py-3 bg-white border rounded-lg justify-center items-center relative cursor-pointer hover:bg-gray-50"
                            id="sortByButton">
                            <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                    d="M5 7h14M5 12h14M5 17h10" />
                            </svg>
                            <p class="font-semibold text-black">Sort By</p>
                            <!-- Pop Up Sort by -->
                            <div class="grid-cols-1 divide-y divide-gray-200 absolute w-[7.5rem] bg-white shadow-sm rounded-lg border top-[3.4rem] right-[0.1px] hidden overflow-hidden"
                                id="sortByPopup">
                                <div class="py-2 px-4 cursor-pointer sort-option hover:bg-[#4268F6] hover:text-white" data-sortby="id">ID</div>
                                <div class="py-2 px-4 cursor-pointer sort-option hover:bg-[#4268F6] hover:text-white" data-sortby="name">Name</div>
                                <div class="py-2 px-4 cursor-pointer sort-option hover:bg-[#4268F6] hover:text-white" data-sortby="role">Role</div>
                            </div>
                        </button>
                    </div>
                    <!-- Form Pencarian -->
                    <form action="{{ route('account.management') }}" method="GET" class="relative" id="searchForm">
                        <input type="search" name="search" id="search"
                            class="block w-full px-10 py-3 rounded-lg border border-gray-200 placeholder:font-bold"
                            value="{{ request('search') }}" placeholder="Search" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                    </form>
                    {{-- Button Tambah --}}
                    <a href="{{ route('users.create') }}"
                        class="px-4 py-3 gap-2 bg-[#4268F6] flex flex-row border rounded-lg justify-center items-center">
                        <div class="flex flex-row items-center gap-2">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>
                            <p class="font-semibold text-white">Add User</p>
                        </div>
                    </a>

                    <!-- Dropdown untuk memilih entries per halaman -->
                    <div class="flex items-center border rounded-lg bg-white px-6 py-1">
                        <form action="{{ route('account.management') }}" method="GET" id="perPageForm">
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

            <!-- Container Tabel (akan diperbarui via AJAX) -->
            <div id="userTableContainer">
                @include('users.partials.user-table')
            </div>
        </div>
    </main>
</x-app-layout>
