<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

                {{-- Card 1: Keuntungan Mingguan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Keuntungan Mingguan (Sen-Min)</h3>
                    <div class="h-80">
                        <canvas id="weeklyProfitChart"></canvas>
                    </div>
                </div>

                {{-- Card 2: Status Pemesanan (Desain Ditingkatkan) --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Status Pemesanan</h3>
                    <div class="flex-grow overflow-y-auto space-y-3 pr-2"> {{-- pr-2 untuk padding jika scrollbar muncul --}}
                        @php
                        $statusStyles = [
                            'Baru' => ['icon' => 'heroicon-o-sparkles', 'color' => 'blue', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L1.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.25 12L17 14.188l-1.25-2.188a2.25 2.25 0 00-1.7-1.7L11.81 9l2.188-1.25a2.25 2.25 0 001.7-1.7L17 3.813l1.25 2.188a2.25 2.25 0 001.7 1.7L22.19 9l-2.188 1.25a2.25 2.25 0 00-1.7 1.7z" /></svg>'],
                            'Diproses' => ['icon' => 'heroicon-o-arrow-path', 'color' => 'yellow', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-yellow-500"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>'],
                            'Menunggu Dijemput' => ['icon' => 'heroicon-o-user-group', 'color' => 'purple', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-purple-500"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.247-1.182L16.5 15.532a3.004 3.004 0 00-3.771.091L13.5 17.25m0 0L9.163 9.801a2.967 2.967 0 01.366-3.889 2.967 2.967 0 013.89-.366l2.852 1.426m-.048 3.514l.21-.048a2.967 2.967 0 013.093 2.482 2.967 2.967 0 01-2.482 3.093M12 12l.048.21a2.967 2.967 0 01-2.482 3.093 2.967 2.967 0 01-3.093-2.482m0 0l-.21-.048m.048.21l-1.426-2.852a2.967 2.967 0 01.366-3.889 2.967 2.967 0 013.89-.366M12 12l-1.426 2.852m0 0l2.852 1.426m-2.852-1.426L12 12m0 0l2.852-1.426m0 0L12 12m0 0l-1.426-2.852M12 12L9.148 9.148" /></svg>'], // contoh, bisa diganti
                            'Siap Diantar' => ['icon' => 'heroicon-o-truck', 'color' => 'teal', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-teal-500"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>'],
                            'Selesai' => ['icon' => 'heroicon-o-check-circle', 'color' => 'green', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'],
                            'Diambil' => ['icon' => 'heroicon-o-check-badge', 'color' => 'emerald', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-emerald-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068M15.75 21H9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'], // Mirip selesai
                            'Sudah Diantar' => ['icon' => 'heroicon-o-home-modern', 'color' => 'lime', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-lime-500"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M3 7.5l3 1.5M3 7.5l-1.5.5M2.25 21V8.25c0-.894.356-1.748.988-2.37L21 3.375M12 12.75h.008v.008H12v-.008z" /></svg>'], // Mirip selesai
                            'Dibatalkan' => ['icon' => 'heroicon-o-x-circle', 'color' => 'red', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'],
                            'Default' => ['icon' => 'heroicon-o-question-mark-circle', 'color' => 'gray', 'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>']
                        ];
                        @endphp

                        @if(!empty($dashboardData['statusCounts']) && count($dashboardData['statusCounts']) > 0)
                            @foreach($dashboardData['statusCounts'] as $status => $count)
                                @php
                                    $style = $statusStyles[$status] ?? $statusStyles['Default'];
                                    $bgColor = "bg-{$style['color']}-100 dark:bg-{$style['color']}-800";
                                    $textColor = "text-{$style['color']}-700 dark:text-{$style['color']}-300";
                                    $iconColor = "text-{$style['color']}-500"; // Ikon SVG sudah memiliki warna sendiri
                                    $dotColor = "bg-{$style['color']}-500";
                                @endphp
                                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <div class="flex items-center space-x-3">
                                        {{-- Menggunakan SVG langsung --}}
                                        {!! $style['icon_svg'] !!}
                                        {{-- Atau jika Anda menggunakan komponen Blade untuk ikon:
                                        <x-dynamic-component :component="$style['icon']" class="w-5 h-5 {{ $iconColor }}" />
                                        --}}
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $status }}</span>
                                    </div>
                                    <span class="text-xs font-semibold {{ $textColor }} {{ $bgColor }} px-2.5 py-1 rounded-full">
                                        {{ $count }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center justify-center h-full">
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada data status pemesanan.</p>
                            </div>
                        @endif
                    </div>
                </div>


                {{-- Card 3: Pendapatan Bulanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Pendapatan / Bulan (Tahun Ini)</h3>
                    <div class="h-80">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Card 4: Top 5 Layanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Top 5 Layanan</h3>
                    <div class="h-80">
                        <canvas id="topServicesChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.dashboardData = @json($dashboardData);
    </script>
    {{-- <script src="{{ asset('js/dashboard.js') }}" defer></script> --}}
</x-app-layout>
