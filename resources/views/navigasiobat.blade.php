<x-app-layout>
    <div class="p-10 flex flex-col gap-6">
        {{-- ROW 1 --}}
        <div class="grid grid-cols-4 gap-6 w-full">
            <a href="#" data-url="{{ route('pemesanan.index') }}" class="ajax-link col-span-1">
                <div
                    class="bg-white flex flex-row gap-4 rounded-xl border p-10 items-center h-[10rem] hover:bg-gray-50 duration-100 transition-all">
                    <div class="pr-4 border-r-2">
                        <svg class="w-16 h-16 text-[#4268F6]" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="currentColor"
                            class="icon icon-tabler icons-tabler-filled icon-tabler-clipboard-text">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-2.997 10.83h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m0 -4h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m-1 -9a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z" />
                        </svg>
                    </div>
                    <p class="text-[2.2rem] py-2 px-4">Pemesanan</p>
                </div>
            </a>
        </div>
        <hr>
        {{-- ROW 2 --}}
        <div class="grid grid-cols-4 gap-6 w-full">
            <a href="#" data-url="{{ route('layanan.index') }}"
                class="ajax-link bg-white flex flex-row gap-4 rounded-xl border p-10 items-center h-[10rem] col-start-1 hover:bg-gray-50 transition-all duration-100">
                <div class="pr-4 border-r-2">
                    <svg class="w-16 h-16 text-[#4268F6]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.605l.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-[2.2rem] py-2 px-4 leading-tight">Layanan</p>
            </a>
            <a href="#" data-url="{{ route('transaksis.index') }}"
                class="ajax-link bg-white flex flex-row gap-4 rounded-xl border p-10 items-center h-[10rem] hover:bg-gray-50 transition-all duration-100">
                <div class="pr-4 border-r-2">
                    <svg class="w-16 h-16 text-[#4268F6]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M4 19v2c0 .5523.44772 1 1 1h14c.5523 0 1-.4477 1-1v-2H4Z" />
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M9 3c0-.55228.44772-1 1-1h8c.5523 0 1 .44772 1 1v3c0 .55228-.4477 1-1 1h-2v1h2c.5096 0 .9376.38314.9939.88957L19.8951 17H4.10498l.90116-8.11043C5.06241 8.38314 5.49047 8 6.00002 8H12V7h-2c-.55228 0-1-.44772-1-1V3Zm1.01 8H8.00002v2.01H10.01V11Zm.99 0h2.01v2.01H11V11Zm5.01 0H14v2.01h2.01V11Zm-8.00998 3H10.01v2.01H8.00002V14ZM13.01 14H11v2.01h2.01V14Zm.99 0h2.01v2.01H14V14ZM11 4h6v1h-6V4Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-[2.2rem] py-2 px-4 leading-tight">Transaksi</p>
            </a>
            <a href="#" data-url="{{ route('riwayat.index') }}"
                class="ajax-link bg-white flex flex-row gap-4 rounded-xl border p-10 items-center h-[10rem] hover:bg-gray-50 transition-all duration-100">
                <div class="pr-4 border-r-2">
                    <svg class="w-16 h-16 text-[#4268F6]" xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-logs"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 12h.01" /><path d="M4 6h.01" /><path d="M4 18h.01" /><path d="M8 18h2" /><path d="M8 12h2" /><path d="M8 6h2" /><path d="M14 6h6" /><path d="M14 12h6" /><path d="M14 18h6" /></svg>
                </div>
                <p class="text-[2.2rem] py-2 px-4 leading-tight">Riwayat Pemesanan</p>
            </a>
        </div>
        <hr>
        {{-- ROW 3 --}}
        <div class="grid grid-cols-4 gap-6 w-full">
            <a href="#" data-url="{{ route('satuan_obats.index') }}"
                class="ajax-link bg-white flex flex-row gap-4 rounded-xl border p-10 items-center h-[10rem] hover:bg-gray-50 transition-all duration-100">
                <div class="pr-4 border-r-2">
                    <svg class="w-16 h-16 text-[#4268F6]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M5 9a7 7 0 1 1 8 6.93V21a1 1 0 1 1-2 0v-5.07A7.001 7.001 0 0 1 5 9Zm5.94-1.06A1.5 1.5 0 0 1 12 7.5a1 1 0 1 0 0-2A3.5 3.5 0 0 0 8.5 9a1 1 0 0 0 2 0c0-.398.158-.78.44-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-[2.2rem] py-2 px-4 leading-tight">Satuan Obat</p>
            </a>
            <a href="#" data-url="{{ route('kategori_obats.index') }}"
                class="ajax-link bg-white flex flex-row gap-4 rounded-xl border p-10 items-center h-[10rem] hover:bg-gray-50 transition-all duration-100">
                <div class="pr-4 border-r-2">
                    <svg class="w-16 h-16 text-[#4268F6]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M4.857 3A1.857 1.857 0 0 0 3 4.857v4.286C3 10.169 3.831 11 4.857 11h4.286A1.857 1.857 0 0 0 11 9.143V4.857A1.857 1.857 0 0 0 9.143 3H4.857Zm10 0A1.857 1.857 0 0 0 13 4.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 9.143V4.857A1.857 1.857 0 0 0 19.143 3h-4.286Zm-10 10A1.857 1.857 0 0 0 3 14.857v4.286C3 20.169 3.831 21 4.857 21h4.286A1.857 1.857 0 0 0 11 19.143v-4.286A1.857 1.857 0 0 0 9.143 13H4.857ZM18 14a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-[2.2rem] py-2 px-4 leading-tight">Kategori Obat</p>
            </a>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    @vite(['resources/js/sidebar.js'])
@endpush
