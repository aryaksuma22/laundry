<div class="h-full flex flex-col px-4 py-5 bg-white">
    <div class="flex flex-row gap-5 p-6 mb-2">
        <img src="{{ url('new.jpg') }}" alt="" class="rounded-full h-[50px] w-[50px]">
        <div class="flex flex-col justify-center">
            <p class="font-semibold">Fachry Alfarissi</p>
            <p class="font-normal text-gray-500 text-sm">Admin</p>
        </div>
    </div>
    <div class="flex flex-col p-4">
        <!-- Dashboard Link -->
        <a href="#" data-url="{{ route('dashboard') }}"
            class="ajax-link group rounded-xl flex flex-row gap-4 p-4 {{ request()->routeIs('dashboard') ? 'bg-[#4268F6] text-white' : 'text-gray-800 hover:text-[#4268F6]' }}">
            <svg id="sidebarSVG"
                class="w-6 h-6 group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-800 group-hover:text-[#4268F6] ' }}"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                viewBox="0 0 24 24">
                <path
                    d="M5 3a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5Zm14 18a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4ZM5 11a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H5Zm14 2a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4Z" />
            </svg>
            <p
                class="group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('dashboard') ? 'font-semibold' : 'font-semibold group-hover:text-[#4268F6]' }}">
                Dashboard</p>
        </a>

        {{-- Database Obat Link --}}
        <a href="#" data-url="{{ route('navigasiobat') }}"
            class="dropdown-parent group rounded-xl flex flex-row gap-4 p-4 items-center
          {{ request()->routeIs('navigasiobat') ? 'bg-[#4268F6] text-white' : 'text-gray-800 hover:text-[#4268F6]' }}">
            <svg id="sidebarSVG"
                class="w-6 h-6 group-hover:transition-all group-hover:duration-200
         {{ request()->routeIs('navigasiobat') ? 'text-white' : 'text-gray-800 group-hover:text-[#4268F6]' }}"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                viewBox="0 0 24 24">
                <!-- Path SVG -->
                <path fill-rule="evenodd"
                    d="M5.617 2.076a1 1 0 0 1 1.09.217L8 3.586l1.293-1.293a1 1 0 0 1 1.414 0L12 3.586l1.293-1.293a1 1 0 0 1 1.414 0L16 3.586l1.293-1.293A1 1 0 0 1 19 3v18a1 1 0 0 1-1.707.707L16 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L12 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L8 20.414l-1.293 1.293A1 1 0 0 1 5 21V3a1 1 0 0 1 .617-.924Z"
                    clip-rule="evenodd" />
            </svg>
            <p
                class="group-hover:transition-all group-hover:duration-200
          {{ request()->routeIs('navigasiobat') ? 'font-semibold' : 'font-semibold group-hover:text-[#4268F6]' }}">
                DatabaseLlaundry
            </p>
            <!-- Ikon panah untuk toggle dropdown -->
            <svg id="sidebarSVG"
                class="w-4 h-4 ml-auto arrow-icon transition-transform duration-200
         {{ request()->routeIs('navigasiobat') ? 'text-white rotate-90' : 'text-gray-800' }}"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        {{-- Dropdown Menu (hidden secara default) --}}
        <div class="dropdown-menu hidden flex-col">
            <a href="#" data-url="{{ route('pemesanan.index') }}"
                class="ajax-link rounded-xl flex flex-row items-center group py-4 px-8 gap-4">
                <svg id="sidebarSVG" class="w-6 h-6 group-hover:transition-all group-hover:duration-150"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-clipboard-text">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-2.997 10.83h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m0 -4h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m-1 -9a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z" />
                </svg>
                <p class="font-semibold">Pemesanan</p>
            </a>
            <a href="#" data-url="{{ route('layanan.index') }}"
                class="ajax-link rounded-xl flex flex-row items-center group py-4 px-8 gap-4">
                <svg id="sidebarSVG" class="w-6 h-6 group-hover:transition-all group-hover:duration-150"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.605l.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z"
                        clip-rule="evenodd" />
                </svg>
                <p class="font-semibold">Layanan</p>
            </a>
            <a href="#" data-url="{{ route('transaksi.index') }}"
                class="ajax-link rounded-xl flex flex-row items-center group py-4 px-8 gap-4">
                <svg id="sidebarSVG" class="w-6 h-6 group-hover:transition-all group-hover:duration-150"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    viewBox="0 0 24 24">
                    <path fill="currentColor" d="M4 19v2c0 .5523.44772 1 1 1h14c.5523 0 1-.4477 1-1v-2H4Z" />
                    <path fill="currentColor" fill-rule="evenodd"
                        d="M9 3c0-.55228.44772-1 1-1h8c.5523 0 1 .44772 1 1v3c0 .55228-.4477 1-1 1h-2v1h2c.5096 0 .9376.38314.9939.88957L19.8951 17H4.10498l.90116-8.11043C5.06241 8.38314 5.49047 8 6.00002 8H12V7h-2c-.55228 0-1-.44772-1-1V3Zm1.01 8H8.00002v2.01H10.01V11Zm.99 0h2.01v2.01H11V11Zm5.01 0H14v2.01h2.01V11Zm-8.00998 3H10.01v2.01H8.00002V14ZM13.01 14H11v2.01h2.01V14Zm.99 0h2.01v2.01H14V14ZM11 4h6v1h-6V4Z"
                        clip-rule="evenodd" />
                </svg>
                <p class="font-semibold">Transaksi</p>
            </a>
            <a href="#" data-url="{{ route('riwayat.index') }}"
                class="ajax-link rounded-xl flex flex-row items-center group py-4 px-8 gap-4">
                <svg id="sidebarSVG" class="w-6 h-6 group-hover:transition-all group-hover:duration-150"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-logs">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 12h.01" />
                    <path d="M4 6h.01" />
                    <path d="M4 18h.01" />
                    <path d="M8 18h2" />
                    <path d="M8 12h2" />
                    <path d="M8 6h2" />
                    <path d="M14 6h6" />
                    <path d="M14 12h6" />
                    <path d="M14 18h6" />
                </svg>
                <p class="font-semibold">Riwayat pemesanan</p>
            </a>
            <a href="#" data-url="{{ route('satuan_obats.index') }}"
                class="ajax-link rounded-xl flex flex-row items-center group py-4 px-8 gap-4">
                <svg id="sidebarSVG" class="w-6 h-6 group-hover:transition-all group-hover:duration-150"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M5 9a7 7 0 1 1 8 6.93V21a1 1 0 1 1-2 0v-5.07A7.001 7.001 0 0 1 5 9Zm5.94-1.06A1.5 1.5 0 0 1 12 7.5a1 1 0 1 0 0-2A3.5 3.5 0 0 0 8.5 9a1 1 0 0 0 2 0c0-.398.158-.78.44-1.06Z"
                        clip-rule="evenodd" />
                </svg>
                <p class="font-semibold">Satuan Obat</p>
            </a>
            <a href="#" data-url="{{ route('kategori_obats.index') }}"
                class="ajax-link rounded-xl flex flex-row items-center group py-4 px-8 gap-4">
                <svg id="sidebarSVG"
                    class="w-6 h-6 group-hover:transition-all group-hover:duration-150 aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M4.857 3A1.857 1.857 0 0 0 3 4.857v4.286C3 10.169 3.831 11 4.857 11h4.286A1.857 1.857 0 0 0 11 9.143V4.857A1.857 1.857 0 0 0 9.143 3H4.857Zm10 0A1.857 1.857 0 0 0 13 4.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 9.143V4.857A1.857 1.857 0 0 0 19.143 3h-4.286Zm-10 10A1.857 1.857 0 0 0 3 14.857v4.286C3 20.169 3.831 21 4.857 21h4.286A1.857 1.857 0 0 0 11 19.143v-4.286A1.857 1.857 0 0 0 9.143 13H4.857ZM18 14a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2Z"
                        clip-rule="evenodd" />
                </svg>
                <p class="font-semibold">Kategori Obat</p>
            </a>
        </div>


        <!-- Account Management Link -->
        <a href="#" data-url="{{ route('account.management') }}"
            class="ajax-link group rounded-xl flex flex-row gap-4 p-4 {{ request()->routeIs('account.management') ? 'bg-[#4268F6] text-white' : 'text-gray-800 hover:text-[#4268F6]' }}">
            <svg id="sidebarSVG"
                class="w-6 h-6 group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('account.management') ? 'text-white' : 'text-gray-800 group-hover:text-[#4268F6] ' }}"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z"
                    clip-rule="evenodd" />
            </svg>
            <p
                class="group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('account.management') ? 'font-semibold' : 'font-semibold group-hover:text-[#4268F6]' }}">
                Account Management</p>
        </a>

        <!-- Settings Link -->
        <a href="#" data-url="{{ route('settings') }}"
            class="ajax-link group rounded-xl flex flex-row gap-4 p-4 {{ request()->routeIs('settings') ? 'bg-[#4268F6] text-white' : 'text-gray-800 hover:text-[#4268F6]' }}">
            <svg id="sidebarSVG"
                class="w-6 h-6 group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('settings') ? 'text-white' : 'text-gray-800 group-hover:text-[#4268F6] ' }}"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M9.586 2.586A2 2 0 0 1 11 2h2a2 2 0 0 1 2 2v.089l.473.196.063-.063a2.002 2.002 0 0 1 2.828 0l1.414 1.414a2 2 0 0 1 0 2.827l-.063.064.196.473H20a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-.089l-.196.473.063.063a2.002 2.002 0 0 1 0 2.828l-1.414 1.414a2 2 0 0 1-2.828 0l-.063-.063-.473.196V20a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-.089l-.473-.196-.063.063a2.002 2.002 0 0 1-2.828 0l-1.414-1.414a2 2 0 0 1 0-2.827l.063-.064L4.089 15H4a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2h.09l.195-.473-.063-.063a2 2 0 0 1 0-2.828l1.414-1.414a2 2 0 0 1 2.827 0l.064.063L9 4.089V4a2 2 0 0 1 .586-1.414ZM8 12a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z"
                    clip-rule="evenodd" />
            </svg>
            <p
                class="group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('settings') ? 'font-semibold' : 'font-semibold group-hover:text-[#4268F6]' }}">
                Settings</p>
        </a>
    </div>

</div>
