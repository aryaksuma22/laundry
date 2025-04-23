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
                Database laundry
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
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M10.7367 14.5876c.895.2365 2.8528.754 3.1643-.4966.3179-1.2781-1.5795-1.7039-2.5053-1.9117-.1034-.0232-.1947-.0437-.2694-.0623l-.6025 2.4153c.0611.0152.1328.0341.2129.0553Zm.8452-3.5291c.7468.1993 2.3746.6335 2.6581-.5025.2899-1.16213-1.2929-1.5124-2.066-1.68348-.0869-.01923-.1635-.03619-.2262-.0518l-.5462 2.19058c.0517.0129.1123.0291.1803.0472Z" />
                    <path fill="currentColor" fill-rule="evenodd"
                        d="M9.57909 21.7008c5.35781 1.3356 10.78401-1.9244 12.11971-7.2816 1.3356-5.35745-1.9247-10.78433-7.2822-12.11995C9.06034.963624 3.6344 4.22425 2.2994 9.58206.963461 14.9389 4.22377 20.3652 9.57909 21.7008ZM14.2085 8.0526c1.3853.47719 2.3984 1.1925 2.1997 2.5231-.1441.9741-.6844 1.4456-1.4013 1.6116.9844.5128 1.485 1.2987 1.0078 2.6612-.5915 1.6919-1.9987 1.8347-3.8697 1.4807l-.454 1.8196-1.0972-.2734.4481-1.7953c-.2844-.0706-.575-.1456-.8741-.2269l-.44996 1.8038-1.09594-.2735.45407-1.8234c-.10059-.0258-.20185-.0522-.30385-.0788-.15753-.0411-.3168-.0827-.47803-.1231l-1.42812-.3559.54468-1.2563s.80844.215.7975.1991c.31063.0769.44844-.1256.50282-.2606l.71781-2.8766.11562.0288c-.04375-.0175-.08343-.0288-.11406-.0366l.51188-2.05344c.01375-.23312-.06688-.52719-.51125-.63812.01718-.01157-.79688-.19813-.79688-.19813l.29188-1.17187 1.51313.37781-.0013.00562c.2275.05657.4619.11032.7007.16469l.4497-1.80187 1.0965.27343-.4406 1.76657c.2944.06718.5906.135.8787.20687l.4375-1.755 1.0975.27344-.4493 1.8025Z"
                        clip-rule="evenodd" />
                </svg>
                <p class="font-semibold">pemesanan</p>
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
                <p class="font-semibold">layanan</p>
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
                <p class="font-semibold">transaksi</p>
            </a>
            <a href="#" data-url="{{ route('riwayat.index') }}"
                class="ajax-link rounded-xl flex flex-row items-center group py-4 px-8 gap-4">
                <svg id="sidebarSVG" class="w-6 h-6 group-hover:transition-all group-hover:duration-150"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 0 0-2 2v9a1 1 0 0 0 1 1h.535a3.5 3.5 0 1 0 6.93 0h3.07a3.5 3.5 0 1 0 6.93 0H21a1 1 0 0 0 1-1v-4a.999.999 0 0 0-.106-.447l-2-4A1 1 0 0 0 19 6h-5a2 2 0 0 0-2-2H4Zm14.192 11.59.016.02a1.5 1.5 0 1 1-.016-.021Zm-10 0 .016.02a1.5 1.5 0 1 1-.016-.021Zm5.806-5.572v-2.02h4.396l1 2.02h-5.396Z"
                        clip-rule="evenodd" />
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
                <svg id="sidebarSVG" class="w-6 h-6 group-hover:transition-all group-hover:duration-150 aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
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
