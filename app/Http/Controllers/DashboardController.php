<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1) Keuntungan per Minggu (Senin - Minggu, minggu ini)
        $startOfWeek = now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = now()->endOfWeek(Carbon::SUNDAY);

        $rawWeeklyProfit = Pemesanan::selectRaw('DAYOFWEEK(tanggal_pesan) as day_of_week_mysql, SUM(total_harga) as total_profit')
            ->whereBetween('tanggal_pesan', [$startOfWeek, $endOfWeek])
            // ->whereIn('status_pesanan', ['Selesai', 'Diambil', 'Sudah Diantar']) // Opsional
            ->groupBy('day_of_week_mysql')
            ->pluck('total_profit', 'day_of_week_mysql')
            ->toArray();

        $weeklyProfitLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $weeklyProfitData = array_fill(0, 7, 0);

        foreach ($rawWeeklyProfit as $dayMysql => $profit) {
            if ($dayMysql == 1) {
                $weeklyProfitData[6] = (float) $profit;
            } else {
                $weeklyProfitData[$dayMysql - 2] = (float) $profit;
            }
        }

        // 2) Pendapatan per Bulan (Tahunan)
        $rawRevenue = Pemesanan::selectRaw('MONTH(tanggal_pesan) as m, SUM(total_harga) as total')
            ->whereYear('tanggal_pesan', now()->year)
            // ->whereIn('status_pesanan', ['Selesai', 'Diambil', 'Sudah Diantar']) // Opsional
            ->groupBy('m')
            ->pluck('total', 'm')
            ->toArray();
        $revenueData = array_replace(array_fill(1, 12, 0), $rawRevenue);

        // 3) Status Pemesanan (Data untuk daftar statistik)
        $statusCounts = Pemesanan::selectRaw('status_pesanan, COUNT(*) as total')
            ->groupBy('status_pesanan')
            ->orderBy('status_pesanan') // Urutkan berdasarkan nama status untuk konsistensi
            ->pluck('total', 'status_pesanan')
            ->toArray();

        // 4) Top 5 Layanan
        $topRaw = Pemesanan::selectRaw('layanan_utama_id, COUNT(*) as total')
            ->groupBy('layanan_utama_id')
            ->orderByDesc('total')
            ->with('layananUtama')
            ->limit(5)
            ->get();
        $topServicesLabels = $topRaw->pluck('layananUtama.nama_layanan')->toArray();
        $topServicesData   = $topRaw->pluck('total')->toArray();

        $dashboardData = [
            'weeklyProfitLabels' => $weeklyProfitLabels,
            'weeklyProfitData' => $weeklyProfitData,
            'revenue' => array_values($revenueData),
            'statusCounts' => $statusCounts, // Mengganti 'status' dengan 'statusCounts'
            'topServices' => [
                'labels' => $topServicesLabels,
                'data' => $topServicesData,
            ]
        ];

        return view('dashboard', compact('dashboardData'));
    }
}
