<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Grid container untuk semua card --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

                {{-- Card 1: Keuntungan Mingguan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Keuntungan Mingguan (Sen-Min)</h3>
                    <div class="h-80"> {{-- Kontainer dengan tinggi tetap untuk chart --}}
                        <canvas id="weeklyProfitChart"></canvas>
                    </div>
                </div>

                {{-- Card 2: Status Pemesanan (diganti menjadi list) --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Status Pemesanan</h3>
                    <div class="space-y-2 flex-grow overflow-y-auto pr-2"> {{-- Tambahkan pr-2 jika scrollbar muncul agar tidak menempel --}}
                        @if(!empty($dashboardData['statusCounts']) && count($dashboardData['statusCounts']) > 0)
                            @foreach($dashboardData['statusCounts'] as $status => $count)
                                <div class="flex justify-between items-center text-sm py-1">
                                    <span class="text-gray-600 dark:text-gray-400">{{ $status }}</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 px-2.5 py-0.5 rounded-full text-xs">
                                        {{ $count }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada data status pemesanan.</p>
                        @endif
                    </div>
                </div>

                {{-- Card 3: Pendapatan Bulanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Pendapatan / Bulan (Tahun Ini)</h3>
                    <div class="h-80"> {{-- Kontainer dengan tinggi tetap untuk chart --}}
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Card 4: Top 5 Layanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Top 5 Layanan</h3>
                    <div class="h-80"> {{-- Kontainer dengan tinggi tetap untuk chart --}}
                        <canvas id="topServicesChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.dashboardData = @json($dashboardData);
    </script>
    {{-- Jika dashboard.js adalah file terpisah dan belum di-bundle, load di sini:
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    --}}
</x-app-layout>
