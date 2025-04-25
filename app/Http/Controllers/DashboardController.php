<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;

class DashboardController extends Controller
{
    public function index()
    {
        // 1) Pesanan per Tahun
        $rawYearly = Pemesanan::selectRaw('YEAR(tanggal) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total','year')
            ->toArray();
        $yearLabels   = array_keys($rawYearly);
        $yearlyOrders = array_values($rawYearly);

        // 2) Pendapatan per Bulan
        $rawRevenue = Pemesanan::selectRaw('MONTH(tanggal) as m, SUM(total_harga) as total')
            ->groupBy('m')
            ->pluck('total','m')
            ->toArray();
        $revenueData = array_replace(array_fill(0,12,0), $rawRevenue);

        // 3) Status Pemesanan
        $rawStatus = Pemesanan::selectRaw('status_pesanan, COUNT(*) as total')
            ->groupBy('status_pesanan')
            ->pluck('total','status_pesanan')
            ->toArray();
        $statusLabels = array_keys($rawStatus);
        $statusData   = array_values($rawStatus);

        // 4) Top 5 Layanan
        $topRaw = Pemesanan::selectRaw('layanan_id, COUNT(*) as total')
            ->groupBy('layanan_id')
            ->orderByDesc('total')
            ->with('layanan')
            ->limit(5)
            ->get();
        $topServicesLabels = $topRaw->pluck('layanan.nama_layanan')->toArray();
        $topServicesData   = $topRaw->pluck('total')->toArray();

        return view('dashboard', compact(
            'yearLabels','yearlyOrders',
            'revenueData',
            'statusLabels','statusData',
            'topServicesLabels','topServicesData'
        ));
    }
}
