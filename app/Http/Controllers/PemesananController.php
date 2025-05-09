<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Layanan;
use App\Models\Setting; // <--- TAMBAHKAN IMPORT INI
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PemesananController extends Controller
{
    /**
     * Helper function to get additional costs from settings.
     *
     * @return array
     */
    private function getBiayaTambahanFromSettings(): array
    {
        return [
            'kecepatan' => [
                'Reguler' => (float)Setting::getValue('biaya_kecepatan_reguler', 0),
                'Express' => (float)Setting::getValue('biaya_kecepatan_express', 10000),
                'Kilat'   => (float)Setting::getValue('biaya_kecepatan_kilat', 20000),
            ],
            'metode' => [
                'Antar Jemput'                 => (float)Setting::getValue('biaya_metode_antar_jemput', 5000),
                'Antar Sendiri Minta Diantar'  => (float)Setting::getValue('biaya_metode_antar_sendiri_minta_diantar', 3000),
                'Minta Dijemput Ambil Sendiri' => (float)Setting::getValue('biaya_metode_minta_dijemput_ambil_sendiri', 3000),
                'Datang Langsung'              => (float)Setting::getValue('biaya_metode_datang_langsung', 0),
            ]
        ];
    }

    /**
     * Update only the status of an order (inline edit via AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $allowedStatuses = ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];

        $validator = Validator::make($request->all(), [
            'status_pesanan' => ['required', 'string', Rule::in($allowedStatuses)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('status_pesanan')
            ], 422);
        }

        $newStatus = $request->input('status_pesanan');
        $oldStatus = $pemesanan->status_pesanan;

        $pemesanan->status_pesanan = $newStatus;

        if (
            in_array($newStatus, ['Selesai', 'Diambil', 'Sudah Diantar']) &&
            is_null($pemesanan->tanggal_selesai)
        ) {
            $pemesanan->tanggal_selesai = now();
        } elseif (
            !in_array($newStatus, ['Selesai', 'Diambil', 'Sudah Diantar']) &&
            in_array($oldStatus, ['Selesai', 'Diambil', 'Sudah Diantar']) &&
            !is_null($pemesanan->tanggal_selesai)
        ) {
            // $pemesanan->tanggal_selesai = null; // Uncomment jika ingin reset
        }

        try {
            $pemesanan->save();
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui.',
                'new_status' => $pemesanan->status_pesanan,
                'status_badge_class' => $pemesanan->status_badge_class,
                'tanggal_selesai_formatted' => $pemesanan->tanggal_selesai ? $pemesanan->tanggal_selesai->isoFormat('DD MMM YYYY, HH:mm') : null,
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal update status pesanan #{$id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan internal.'], 500);
        }
    }

    /**
     * Get data for quick view modal via AJAX.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showQuickView($id)
    {
        try {
            $pemesanan = Pemesanan::with(['layananUtama', 'transaksi'])->findOrFail($id);
            $html = view('pemesanan.partials.quick-view-modal-content', compact('pemesanan'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Data pemesanan tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error quick view pemesanan #{$id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat detail pemesanan.'], 500);
        }
    }

    public function index(Request $request)
    {
        $sortOrder = $request->get('sortOrder', 'desc');
        $sortBy    = $request->get('sortBy', 'tanggal_pesan');
        $perPage = $request->get('perPage', 10);
        $search  = $request->get('search', '');
        $filterMetode = $request->get('filter_metode', '');
        $filterLayanan = $request->get('filter_layanan', '');
        $filterStatus = $request->get('filter_status', '');
        $filterStatusBayar = $request->get('filter_status_bayar', '');

        $allowedSort = ['nama_pelanggan', 'no_pesanan', 'tanggal_pesan', 'status_pesanan', 'total_harga', 'metode_layanan', 'kontak_pelanggan', 'status_pembayaran'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'tanggal_pesan';
        }

        $query = Pemesanan::with(['layananUtama', 'transaksi']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_pesanan', 'like', "%{$search}%")
                    // ... (pencarian lainnya)
                    ->orWhereHas('transaksi', function ($subq) use ($search) {
                        $subq->where('status_pembayaran', 'like', "%{$search}%");
                    });
            });
        }

        $query->when($filterMetode, fn($q, $metode) => $q->where('metode_layanan', $metode));
        $query->when($filterLayanan, fn($q, $layananId) => $q->where('layanan_utama_id', $layananId));
        $query->when($filterStatus, fn($q, $status) => $q->where('status_pesanan', $status));

        $query->when($filterStatusBayar, function ($q, $status) {
            if ($status === 'Lunas') {
                $q->whereHas('transaksi', fn($tq) => $tq->where('status_pembayaran', 'Lunas'));
            } elseif ($status === 'Belum Lunas') {
                $q->where(function ($subq) {
                    $subq->doesntHave('transaksi')
                        ->orWhereHas('transaksi', fn($tq) => $tq->where('status_pembayaran', 'Belum Lunas'));
                });
            }
        });

        $pemesanans = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);
        $filterData = [];
        if (!$request->ajax() || !$request->hasAny(['page', 'search', 'sortBy', 'sortOrder', 'perPage', 'filter_metode', 'filter_layanan', 'filter_status', 'filter_status_bayar'])) {
            $filterData['metodeOptions'] = Pemesanan::query()->distinct()->pluck('metode_layanan')->sort()->toArray();
            $filterData['layananOptions'] = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan']);
            $filterData['statusOptions'] = ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];
            $filterData['statusBayarOptions'] = ['Belum Lunas', 'Lunas'];
        }

        if ($request->ajax() && $request->hasAny(['page', 'search', 'sortBy', 'sortOrder', 'perPage', 'filter_metode', 'filter_layanan', 'filter_status', 'filter_status_bayar'])) {
            try {
                $html = view('pemesanan.partials.table', compact('pemesanans'))->render();
                return response()->json(['html' => $html]);
            } catch (\Exception $e) {
                Log::error('Error rendering pemesanan partial table: ' . $e->getMessage());
                return response()->json(['error' => 'Gagal memuat tabel.'], 500);
            }
        }

        return view('pemesanan.index', compact(
            'pemesanans',
            'search',
            'sortBy',
            'sortOrder',
            'perPage',
            'filterMetode',
            'filterLayanan',
            'filterStatus',
            'filterStatusBayar',
            'filterData'
        ));
    }

    public function create()
    {
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan', 'harga']);
        $biayaTambahan = $this->getBiayaTambahanFromSettings(); // Ambil dari settings

        $statuses = ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];
        $kecepatanOptions = array_keys($biayaTambahan['kecepatan']); // Ambil dari keys biayaTambahan
        $metodeOptions = array_keys($biayaTambahan['metode']);     // Ambil dari keys biayaTambahan

        return view('pemesanan.create', compact(
            'layanans',
            'statuses',
            'kecepatanOptions',
            'metodeOptions',
            'biayaTambahan'
        ));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kontak_pelanggan' => 'required|string|max:100',
            'alamat_pelanggan' => 'nullable|string|max:1000',
            'layanan_utama_id' => 'required|exists:layanans,id',
            'kecepatan_layanan' => 'required|string|max:50',
            'metode_layanan' => 'required|string|max:100',
            'estimasi_berat' => 'nullable|numeric|min:0',
            'daftar_item' => 'nullable|string',
            'catatan_pelanggan' => 'nullable|string',
            'tanggal_penjemputan' => 'nullable|date|required_if:metode_layanan,Antar Jemput,Minta Dijemput Ambil Sendiri',
            'waktu_penjemputan' => 'nullable|string|max:50|required_if:metode_layanan,Antar Jemput,Minta Dijemput Ambil Sendiri',
            'instruksi_alamat' => 'nullable|string',
            'status_pesanan' => 'required|string|max:100',
            'berat_final' => 'nullable|numeric|min:0',
            'total_harga' => 'nullable|numeric|min:0', // Di form create, ini diisi otomatis oleh JS
            'kode_promo' => 'nullable|string|max:50',
            'tanggal_selesai' => 'nullable|date',
        ]);

        // Generate Nomor Pesanan Otomatis dari Settings
        $prefixAdmin = Setting::getValue('prefix_no_pesanan_admin', 'ADM-');
        $randomLength = (int)Setting::getValue('format_no_pesanan_random_length', 4);

        $noPesanan = $prefixAdmin . now()->format('ymd') . strtoupper(Str::random($randomLength));
        while (Pemesanan::where('no_pesanan', $noPesanan)->exists()) {
            $noPesanan = $prefixAdmin . now()->format('ymd') . strtoupper(Str::random($randomLength + 1));
        }
        $validatedData['no_pesanan'] = $noPesanan;
        $validatedData['tanggal_pesan'] = now();

        Pemesanan::create($validatedData);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan berhasil ditambahkan oleh Admin.');
    }

    public function showGuestOrderForm()
    {
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan', 'harga']);
        $biayaTambahan = $this->getBiayaTambahanFromSettings(); // Ambil dari settings

        // Kecepatan dan metode options bisa diambil dari keys $biayaTambahan jika diperlukan di view guest
        // $kecepatanOptions = array_keys($biayaTambahan['kecepatan']);
        // $metodeOptions = array_keys($biayaTambahan['metode']);

        return view('guest.order_form', compact('layanans', 'biayaTambahan'));
    }

    public function storeGuestOrder(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pelanggan'    => 'required|string|max:255',
            'kontak_pelanggan'  => 'required|string|max:100',
            'alamat_pelanggan'  => [
                Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri'])),
                'nullable',
                'string',
                'max:1000'
            ],
            'metode_layanan'    => 'required|string|in:Datang Langsung,Antar Jemput,Antar Sendiri Minta Diantar,Minta Dijemput Ambil Sendiri',
            'layanan_utama_id'  => 'required|exists:layanans,id',
            'kecepatan_layanan' => 'required|string|in:Reguler,Express,Kilat',
            'estimasi_berat'    => [ /* ... validasi estimasi_berat ... */],
            'daftar_item'       => [ /* ... validasi daftar_item ... */],
            'catatan_pelanggan' => 'nullable|string|max:1000',
            'tanggal_penjemputan' => ['nullable', 'date', 'after_or_equal:today', Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Dijemput Ambil Sendiri']))],
            'waktu_penjemputan' => ['nullable', 'string', 'max:50', Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Dijemput Ambil Sendiri']))],
            'instruksi_alamat'  => 'nullable|string|max:1000',
            'kode_promo'        => 'nullable|string|max:50',
        ], [ /* ... custom messages ... */]);

        // Generate Nomor Pesanan Otomatis dari Settings
        $prefixGuest = Setting::getValue('prefix_no_pesanan_guest', 'LNDRY-');
        $randomLength = (int)Setting::getValue('format_no_pesanan_random_length', 4);

        $noPesanan = $prefixGuest . now()->format('ymd') . strtoupper(Str::random($randomLength));
        while (Pemesanan::where('no_pesanan', $noPesanan)->exists()) {
            $noPesanan = $prefixGuest . now()->format('ymd') . strtoupper(Str::random($randomLength + 1));
        }

        $orderData = $validatedData;
        $orderData['no_pesanan']     = $noPesanan;
        $orderData['tanggal_pesan']  = now();
        $orderData['status_pesanan'] = 'Baru';
        $orderData['berat_final']    = null;
        $orderData['total_harga']    = 0; // Akan dihitung oleh admin atau saat transaksi

        try {
            $pemesanan = Pemesanan::create($orderData);
            return redirect()->route('guest.order.form')
                ->with('swal_success_message', 'Pesanan Anda berhasil dibuat!')
                ->with('order_number', $pemesanan->no_pesanan);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pesanan guest: ' . $e->getMessage() . ' Data: ' . json_encode($orderData));
            return redirect()->route('guest.order.form')
                ->withInput()
                ->with('error', 'Terjadi kesalahan. Pesanan Anda gagal disimpan.');
        }
    }

    public function edit($id)
    {
        $pemesanan = Pemesanan::with('layananUtama')->findOrFail($id);
        $layanans  = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan', 'harga']);
        $biayaTambahan = $this->getBiayaTambahanFromSettings(); // Ambil dari settings

        $statuses = ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];
        $kecepatanOptions = array_keys($biayaTambahan['kecepatan']); // Ambil dari keys biayaTambahan
        $metodeOptions = array_keys($biayaTambahan['metode']);     // Ambil dari keys biayaTambahan

        return view('pemesanan.edit', compact(
            'pemesanan',
            'layanans',
            'statuses',
            'kecepatanOptions',
            'metodeOptions',
            'biayaTambahan'
        ));
    }

    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $validatedData = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kontak_pelanggan' => 'required|string|max:100',
            'alamat_pelanggan' => ['nullable', 'string', 'max:1000', Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri']))],
            'layanan_utama_id' => 'required|exists:layanans,id',
            'kecepatan_layanan' => 'required|string|max:50',
            'metode_layanan' => 'required|string|max:100',
            'estimasi_berat' => 'nullable|numeric|min:0',
            'daftar_item' => 'nullable|string',
            'catatan_pelanggan' => 'nullable|string',
            'tanggal_penjemputan' => ['nullable', 'date', Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Dijemput Ambil Sendiri']))],
            'waktu_penjemputan' => ['nullable', 'string', 'max:50', Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Dijemput Ambil Sendiri']))],
            'instruksi_alamat' => 'nullable|string',
            'berat_final' => 'nullable|numeric|min:0',
            'total_harga' => 'required|numeric|min:0', // Wajib diisi di form edit (dihitung JS)
            'status_pesanan' => 'required|string|max:100',
            'kode_promo' => 'nullable|string|max:50',
            'tanggal_selesai' => 'nullable|date',
        ], [
            'total_harga.required' => 'Total harga (otomatis) gagal dihitung atau kosong.',
        ]);

        if (
            in_array($validatedData['status_pesanan'], ['Selesai', 'Diambil', 'Sudah Diantar']) &&
            is_null($pemesanan->tanggal_selesai) &&
            empty($validatedData['tanggal_selesai']) // Jika belum diisi manual
        ) {
            $validatedData['tanggal_selesai'] = now();
        }

        $pemesanan->update($validatedData);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan (No: ' . $pemesanan->no_pesanan . ') berhasil diperbarui.');
    }

    public function destroy(Request $request) // Untuk Bulk Delete
    {
        $ids = $request->input('ids');
        if (!empty($ids) && is_array($ids)) {
            $deletedCount = Pemesanan::whereIn('id', $ids)->delete();
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $deletedCount . ' Pemesanan berhasil dihapus.']);
            }
            return redirect()->route('pemesanan.index')->with('success', $deletedCount . ' Pemesanan berhasil dihapus.');
        }
        $errorMessage = 'Tidak ada pesanan yang dipilih atau format ID salah.';
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $errorMessage], 400);
        }
        return redirect()->route('pemesanan.index')->with('error', $errorMessage);
    }

    public function destroySingle(Request $request, $id)
    {
        $pemesanan = Pemesanan::find($id);
        if ($pemesanan) {
            $pemesanan->delete();
            return response()->json(['success' => true, 'message' => 'Pemesanan berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
    }
}
