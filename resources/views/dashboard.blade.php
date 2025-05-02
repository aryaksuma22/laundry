<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="p-4 border rounded h-full">
                        <h3 class="mb-2">Pesanan</h3>
                        <canvas id="ordersChart"></canvas>
                    </div>

                    <div class="p-4 border rounded">
                        <h3 class="mb-2">Status Pemesanan</h3>
                        <canvas id="statusChart"></canvas>
                    </div>

                    <div class="p-4 border rounded">
                        <h3 class="mb-2">Pendapatan / Bulan</h3>
                        <canvas id="revenueChart"></canvas>
                    </div>

                    <div class="p-4 border rounded">
                        <h3 class="mb-2">Top 5 Layanan</h3>
                        <canvas id="topServicesChart"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        window.dashboardData = {
            yearLabels: @json($yearLabels),
            yearlyOrders: @json($yearlyOrders),
            revenue: @json(array_values($revenueData)),
            status: {
                labels: @json($statusLabels),
                data: @json($statusData)
            },
            topServices: {
                labels: @json($topServicesLabels),
                data: @json($topServicesData)
            }
        };
    </script>
</x-app-layout>
