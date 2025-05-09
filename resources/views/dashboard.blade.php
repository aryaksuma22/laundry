<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8"> {/* Menambah padding vertikal global */}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-200 dark:bg-gray-900 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8 rounded-lg shadow-lg">

                {{-- Card 1: Keuntungan Mingguan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md"> {/* Menggunakan rounded-xl dan shadow-md */}
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Keuntungan Mingguan (Sen-Min)</h3>
                    <div class="h-80"> {/* Tinggi tetap untuk kontainer canvas */}
                        <canvas id="weeklyProfitChart"></canvas>
                    </div>
                </div>

                {{-- Card 2: Status Pemesanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Status Pemesanan</h3>
                    <div class="h-80"> {/* Tinggi yang sama */}
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                {{-- Card 3: Pendapatan Bulanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Pendapatan / Bulan (Tahun Ini)</h3>
                    <div class="h-80"> {/* Tinggi yang sama */}
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Card 4: Top 5 Layanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Top 5 Layanan</h3>
                    <div class="h-80"> {/* Tinggi yang sama */}
                        <canvas id="topServicesChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Pastikan Chart.js dan chartjs-plugin-datalabels sudah di-load
        // (misalnya melalui app.js atau CDN di layout utama)
        window.dashboardData = @json($dashboardData);
    </script>
    {{-- Jika dashboard.js adalah file terpisah dan belum di-bundle, load di sini:
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    --}}
</x-app-layout>
