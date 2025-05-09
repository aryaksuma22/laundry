<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Carbon\Carbon; // Import Carbon

class DashboardController extends Controller
{
    public function index()
    {
        // 1) Keuntungan per Minggu (Senin - Minggu, minggu ini)
        $startOfWeek = now()->startOfWeek(Carbon::MONDAY); // Senin minggu ini
        $endOfWeek   = now()->endOfWeek(Carbon::SUNDAY);   // Minggu minggu ini

        // Ambil data keuntungan per hari dalam rentang minggu ini
        // DAYOFWEEK() MySQL: 1=Minggu, 2=Senin, ..., 7=Sabtu
        // Kita ingin Senin (index 0) s/d Minggu (index 6)
        $rawWeeklyProfit = Pemesanan::selectRaw('DAYOFWEEK(tanggal_pesan) as day_of_week_mysql, SUM(total_harga) as total_profit')
            ->whereBetween('tanggal_pesan', [$startOfWeek, $endOfWeek])
            // Opsional: Filter status pesanan yang sudah selesai/dibayar jika perlu
            // ->whereIn('status_pesanan', ['Selesai', 'Diambil', 'Sudah Diantar'])
            ->groupBy('day_of_week_mysql')
            ->pluck('total_profit', 'day_of_week_mysql')
            ->toArray();

        $weeklyProfitLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $weeklyProfitData = array_fill(0, 7, 0); // Inisialisasi 7 hari dengan 0

        // Mapping dari DAYOFWEEK() MySQL ke array kita (0=Sen, ..., 6=Min)
        foreach ($rawWeeklyProfit as $dayMysql => $profit) {
            if ($dayMysql == 1) { // Minggu (MySQL)
                $weeklyProfitData[6] = (float) $profit; // Index 6 untuk Minggu
            } else { // Senin-Sabtu (MySQL: 2-7)
                $weeklyProfitData[$dayMysql - 2] = (float) $profit; // Index 0-5 untuk Senin-Sabtu
            }
        }

        // 2) Pendapatan per Bulan (Tahunan)
        $rawRevenue = Pemesanan::selectRaw('MONTH(tanggal_pesan) as m, SUM(total_harga) as total')
            ->whereYear('tanggal_pesan', now()->year) // Filter untuk tahun ini saja
            // Filter status pesanan yang sudah selesai/dibayar jika perlu?
            // ->whereIn('status_pesanan', ['Selesai', 'Diambil', 'Sudah Diantar'])
            ->groupBy('m')
            ->pluck('total', 'm')
            ->toArray();
        $revenueData = array_replace(array_fill(1, 12, 0), $rawRevenue);

        // 3) Status Pemesanan
        $rawStatus = Pemesanan::selectRaw('status_pesanan, COUNT(*) as total')
            ->groupBy('status_pesanan')
            ->pluck('total', 'status_pesanan')
            ->toArray();
        $statusLabels = array_keys($rawStatus);
        $statusData   = array_values($rawStatus);

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
            // Data untuk Keuntungan Mingguan
            'weeklyProfitLabels' => $weeklyProfitLabels,
            'weeklyProfitData' => $weeklyProfitData,

            // Data untuk Pendapatan Bulanan
            'revenue' => array_values($revenueData), // array_values agar index 0-11 untuk JS

            // Data untuk Status Pemesanan
            'status' => [
                'labels' => $statusLabels,
                'data' => $statusData,
            ],

            // Data untuk Top 5 Layanan
            'topServices' => [
                'labels' => $topServicesLabels,
                'data' => $topServicesData,
            ]
        ];

        return view('dashboard', compact('dashboardData'));
    }
}
