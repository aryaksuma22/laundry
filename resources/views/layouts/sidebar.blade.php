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
        <a href="{{ route('dashboard') }}" class="group rounded-xl flex flex-row gap-4 p-4 {{ request()->routeIs('dashboard') ? 'bg-[#4268F6] text-white' : 'text-gray-800 hover:text-[#4268F6]' }}">
            <svg class="w-6 h-6 group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-800 group-hover:text-[#4268F6] ' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5 3a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5Zm14 18a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4ZM5 11a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H5Zm14 2a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4Z"/>
            </svg>
            <p class="group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('dashboard') ? 'font-semibold' : 'font-semibold group-hover:text-[#4268F6]' }}">Dashboard</p>
        </a>

        {{-- Database Obat Link --}}
        <a href="{{ route('navigasiobat') }}" class="group rounded-xl flex flex-row gap-4 p-4 {{ request()->routeIs('navigasiobat') ? 'bg-[#4268F6] text-white' : 'text-gray-800 hover:text-[#4268F6]' }}">
            <svg class="w-6 h-6 group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('navigasiobat') ? 'text-white' : 'text-gray-800 group-hover:text-[#4268F6] ' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M5.617 2.076a1 1 0 0 1 1.09.217L8 3.586l1.293-1.293a1 1 0 0 1 1.414 0L12 3.586l1.293-1.293a1 1 0 0 1 1.414 0L16 3.586l1.293-1.293A1 1 0 0 1 19 3v18a1 1 0 0 1-1.707.707L16 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L12 20.414l-1.293 1.293a1 1 0 0 1-1.414 0L8 20.414l-1.293 1.293A1 1 0 0 1 5 21V3a1 1 0 0 1 .617-.924ZM9 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H9Zm0 4a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Zm0 4a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/>
              </svg>
            <p class="group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('navigasiobat') ? 'font-semibold' : 'font-semibold group-hover:text-[#4268F6]' }}">Database Obat</p>
        </a>

        <!-- Account Management Link -->    
        <a href="{{ route('account.management') }}" class="group rounded-xl flex flex-row gap-4 p-4 {{ request()->routeIs('account.management') ? 'bg-[#4268F6] text-white' : 'text-gray-800 hover:text-[#4268F6]' }}">
            <svg class="w-6 h-6 group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('account.management') ? 'text-white' : 'text-gray-800 group-hover:text-[#4268F6] ' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd"/>
            </svg>
            <p class="group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('account.management') ? 'font-semibold' : 'font-semibold group-hover:text-[#4268F6]' }}">Account Management</p>
        </a>
    
        <!-- Settings Link -->
        <a href="{{ route('settings') }}" class="group rounded-xl flex flex-row gap-4 p-4 {{ request()->routeIs('settings') ? 'bg-[#4268F6] text-white' : 'text-gray-800 hover:text-[#4268F6]' }}">
            <svg class="w-6 h-6 group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('settings') ? 'text-white' : 'text-gray-800 group-hover:text-[#4268F6] ' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M9.586 2.586A2 2 0 0 1 11 2h2a2 2 0 0 1 2 2v.089l.473.196.063-.063a2.002 2.002 0 0 1 2.828 0l1.414 1.414a2 2 0 0 1 0 2.827l-.063.064.196.473H20a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-.089l-.196.473.063.063a2.002 2.002 0 0 1 0 2.828l-1.414 1.414a2 2 0 0 1-2.828 0l-.063-.063-.473.196V20a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-.089l-.473-.196-.063.063a2.002 2.002 0 0 1-2.828 0l-1.414-1.414a2 2 0 0 1 0-2.827l.063-.064L4.089 15H4a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2h.09l.195-.473-.063-.063a2 2 0 0 1 0-2.828l1.414-1.414a2 2 0 0 1 2.827 0l.064.063L9 4.089V4a2 2 0 0 1 .586-1.414ZM8 12a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd"/>
            </svg>
            <p class="group-hover:transition-all group-hover:duration-150 {{ request()->routeIs('settings') ? 'font-semibold' : 'font-semibold group-hover:text-[#4268F6]' }}">Settings</p>
        </a>
    </div>
    
</div>
