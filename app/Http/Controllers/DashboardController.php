<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
// use App\Models\Layanan; // Tidak perlu di-use jika hanya diakses via relasi

class DashboardController extends Controller
{
    public function index()
    {
        // 1) Pesanan per Tahun
        //    Gunakan 'tanggal_pesan' atau 'created_at'
        $rawYearly = Pemesanan::selectRaw('YEAR(tanggal_pesan) as year, COUNT(*) as total') // GANTI: tanggal -> tanggal_pesan
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total', 'year')
            ->toArray();
        $yearLabels   = array_keys($rawYearly);
        $yearlyOrders = array_values($rawYearly);

        // 2) Pendapatan per Bulan
        //    Gunakan 'tanggal_pesan' atau 'created_at'
        $rawRevenue = Pemesanan::selectRaw('MONTH(tanggal_pesan) as m, SUM(total_harga) as total') // GANTI: tanggal -> tanggal_pesan
            // Filter status pesanan yang sudah selesai/dibayar jika perlu?
            // ->whereIn('status_pesanan', ['Selesai', 'Diambil', 'Sudah Diantar'])
            ->groupBy('m')
            ->pluck('total', 'm')
            ->toArray();
        // Pastikan keys adalah 1-12, bukan 0-11 jika MONTH() mengembalikan 1-12
        $revenueData = array_replace(array_fill(1, 12, 0), $rawRevenue); // Mulai dari key 1
        // Ubah keys kembali ke 0-11 jika JS mengharapkannya (meskipun array_values di bawah lebih aman)
        // $revenueData = array_values($revenueData); // Ini akan menghasilkan array 0-11

        // 3) Status Pemesanan (Ini sepertinya sudah OK)
        $rawStatus = Pemesanan::selectRaw('status_pesanan, COUNT(*) as total')
            ->groupBy('status_pesanan')
            ->pluck('total', 'status_pesanan')
            ->toArray();
        $statusLabels = array_keys($rawStatus);
        $statusData   = array_values($rawStatus);

        // 4) Top 5 Layanan
        $topRaw = Pemesanan::selectRaw('layanan_utama_id, COUNT(*) as total') // GANTI: layanan_id -> layanan_utama_id
            ->groupBy('layanan_utama_id') // GANTI: layanan_id -> layanan_utama_id
            ->orderByDesc('total')
            ->with('layananUtama') // GANTI: layanan -> layananUtama
            ->limit(5)
            ->get();
        // GANTI: layanan.nama_layanan -> layananUtama.nama_layanan
        $topServicesLabels = $topRaw->pluck('layananUtama.nama_layanan')->toArray();
        $topServicesData   = $topRaw->pluck('total')->toArray();

        // Data yang dikirim ke view (struktur harus sesuai dengan ekspektasi JS)
        $dashboardData = [
            'yearLabels' => $yearLabels,
            'yearlyOrders' => $yearlyOrders,
            // Kirim array values agar index selalu 0-11 untuk JS
            'revenue' => array_values($revenueData),
            'status' => [
                'labels' => $statusLabels,
                'data' => $statusData,
            ],
            'topServices' => [
                'labels' => $topServicesLabels,
                'data' => $topServicesData,
            ]
        ];

        // Kirim $dashboardData ke view
        return view('dashboard', compact('dashboardData'));
    }
}
