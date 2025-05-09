<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PemesananController extends Controller
{

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

        // Definisikan status yang diizinkan. Anda bisa mengambil ini dari konstanta atau config jika lebih baik.
        $allowedStatuses = ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];

        $validator = Validator::make($request->all(), [
            'status_pesanan' => ['required', 'string', Rule::in($allowedStatuses)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('status_pesanan') // Berikan pesan error spesifik
            ], 422); // HTTP 422 Unprocessable Entity untuk error validasi
        }

        $newStatus = $request->input('status_pesanan');
        $oldStatus = $pemesanan->status_pesanan; // Simpan status lama jika perlu

        $pemesanan->status_pesanan = $newStatus;

        // Logika untuk otomatis set tanggal_selesai
        // Jika status baru adalah salah satu dari status akhir DAN tanggal_selesai belum diisi
        if (
            in_array($newStatus, ['Selesai', 'Diambil', 'Sudah Diantar']) && // Sesuaikan status akhir Anda
            is_null($pemesanan->tanggal_selesai)
        ) {
            $pemesanan->tanggal_selesai = now();
        } elseif (
            // Jika status diubah dari 'Selesai' ke status lain, dan Anda ingin tanggal_selesai di-null-kan lagi
            // Ini opsional, tergantung kebutuhan bisnis Anda
            !in_array($newStatus, ['Selesai', 'Diambil', 'Sudah Diantar']) &&
            in_array($oldStatus, ['Selesai', 'Diambil', 'Sudah Diantar']) &&
            !is_null($pemesanan->tanggal_selesai) // Pastikan ada tanggal selesai sebelumnya
        ) {
            // $pemesanan->tanggal_selesai = null; // Uncomment jika ingin reset tanggal selesai
        }


        try {
            $pemesanan->save();

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui.',
                'new_status' => $pemesanan->status_pesanan,
                // Kirim juga kelas badge baru menggunakan accessor dari model
                'status_badge_class' => $pemesanan->status_badge_class,
                // Kirim tanggal selesai yang diformat jika ada perubahan
                'tanggal_selesai_formatted' => $pemesanan->tanggal_selesai ? $pemesanan->tanggal_selesai->isoFormat('DD MMM YYYY, HH:mm') : null,
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal update status pesanan #{$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal saat memperbarui status.'
            ], 500);
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
            // Eager load relasi yang dibutuhkan untuk quick view
            $pemesanan = Pemesanan::with(['layananUtama', 'transaksi'])->findOrFail($id);

            // Render partial view untuk konten modal
            $html = view('pemesanan.partials.quick-view-modal-content', compact('pemesanan'))->render();

            return response()->json(['success' => true, 'html' => $html]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Quick view gagal: Pemesanan #{$id} tidak ditemukan.");
            return response()->json(['success' => false, 'message' => 'Data pemesanan tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error saat generate quick view untuk pemesanan #{$id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memuat detail pemesanan.'], 500);
        }
    }



    public function index(Request $request)
    {
        // --- Existing Pagination/Sort/Search Params ---
        $sortOrder = $request->get('sortOrder', 'desc');
        $sortBy    = $request->get('sortBy', 'tanggal_pesan');
        $perPage = $request->get('perPage', 10);
        $search  = $request->get('search', '');

        // --- NEW: Filter Params ---
        $filterMetode = $request->get('filter_metode', ''); // Default to empty (all)
        $filterLayanan = $request->get('filter_layanan', '');
        $filterStatus = $request->get('filter_status', '');
        $filterStatusBayar = $request->get('filter_status_bayar', '');
        // --- Existing Allowed Sort Columns ---
        $allowedSort = [ /* ... */'nama_pelanggan', 'no_pesanan', 'tanggal_pesan', 'status_pesanan', 'total_harga', 'metode_layanan', 'kontak_pelanggan', 'status_pembayaran'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'tanggal_pesan';
        }

        // --- Build the Query ---
        $query = Pemesanan::with(['layananUtama', 'transaksi']); // Eager load layanan

        // Apply Search Filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_pesanan', 'like', "%{$search}%")
                    ->orWhere('tanggal_pesan', 'like', "%{$search}%")
                    ->orWhereHas('layananUtama', function ($subq) use ($search) {
                        $subq->where('nama_layanan', 'like', "%{$search}%");
                    })
                    ->orWhere('status_pesanan', 'like', "%{$search}%")
                    ->orWhere('alamat_pelanggan', 'like', "%{$search}%")
                    ->orWhere('kontak_pelanggan', 'like', "%{$search}%")
                    ->orWhereHas('transaksi', function ($subq) use ($search) {
                        $subq->where('status_pembayaran', 'like', "%{$search}%");
                    });
            });
        }

        // --- Apply NEW Dropdown Filters ---
        $query->when($filterMetode, function ($q, $metode) {
            return $q->where('metode_layanan', $metode);
        });

        $query->when($filterLayanan, function ($q, $layananId) {
            return $q->where('layanan_utama_id', $layananId);
        });

        $query->when($filterStatus, function ($q, $status) {
            return $q->where('status_pesanan', $status);
        });

        $query->when($filterStatusBayar, function ($q, $status) {
            if ($status === 'Lunas') {
                // Hanya tampilkan yang punya transaksi dengan status 'Lunas'
                $q->whereHas('transaksi', function ($tq) {
                    $tq->where('status_pembayaran', 'Lunas');
                });
            } elseif ($status === 'Belum Lunas') {
                // Tampilkan yang TIDAK punya transaksi ATAU punya transaksi tapi statusnya 'Belum Lunas'
                $q->where(function ($subq) {
                    $subq->doesntHave('transaksi')
                        ->orWhereHas('transaksi', function ($tq) {
                            $tq->where('status_pembayaran', 'Belum Lunas');
                        });
                });
            }
            // Jika status filter kosong, tidak ada yang dilakukan (tampilkan semua)
        });

        // --- End NEW Filter Application ---


        // Apply Sorting and Paginate
        $pemesanans = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // --- Data for Filters (Only needed for full page load) ---
        $filterData = [];
        if (!$request->ajax() || !$request->hasAny(['page', 'search', 'sortBy', 'sortOrder', 'perPage', 'filter_metode', 'filter_layanan', 'filter_status', 'filter_status_bayar'])) {
            // Fetch data needed to populate the dropdowns ONLY on initial load
            $filterData['metodeOptions'] = Pemesanan::query()->distinct()->pluck('metode_layanan')->sort()->toArray();
            $filterData['layananOptions'] = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan']);
            // Use a predefined list for statuses for consistency
            $filterData['statusOptions'] = ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];
            $filterData['statusBayarOptions'] = ['Belum Lunas', 'Lunas'];
        }


        // Handle AJAX request for table update
        if ($request->ajax() && $request->hasAny(['page', 'search', 'sortBy', 'sortOrder', 'perPage', 'filter_metode', 'filter_layanan', 'filter_status', 'filterStatusBayar'])) {
            Log::info('AJAX request for Pemesanan TABLE update detected (including filters).');
            try {
                // Pass current filter values to the partial view if needed for display (usually not)
                $html = view('pemesanan.partials.table', compact('pemesanans'))->render();
                return response()->json(['html' => $html]);
            } catch (\Exception $e) {
                Log::error('Error rendering pemesanan partial table: ' . $e->getMessage());
                return response()->json(['error' => 'Gagal memuat tabel.'], 500);
            }
        }

        // Render Full View for initial load or non-table AJAX requests
        Log::info('Full page request (or initial AJAX load) for Pemesanan index.');
        return view('pemesanan.index', compact(
            'pemesanans', // Data for initial table render (if JS fails)
            'search',
            'sortBy',
            'sortOrder',
            'perPage',
            // Pass current filter selections
            'filterMetode',
            'filterLayanan',
            'filterStatus',
            'filterStatusBayar',
            // Pass options for filter dropdowns
            'filterData'
        ));
    }

    /**
     * Show the form for creating a new order (ADMIN).
     * Sesuaikan view 'pemesanan.create' untuk field baru.
     */
    public function create()
    {
        // Fetch layanan WITHOUT tipe_layanan
        $layanans = Layanan::orderBy('nama_layanan', 'asc')
            ->get(['id', 'nama_layanan', 'harga']); // Select only existing columns

        // Define additional costs (adjust values as needed)
        $biayaTambahan = [
            'kecepatan' => [
                'Reguler' => 0,
                'Express' => 10000,
                'Kilat' => 20000,
            ],
            'metode' => [
                'Antar Jemput' => 5000,
                'Antar Sendiri Minta Diantar' => 3000,
                'Minta Dijemput Ambil Sendiri' => 3000,
                'Datang Langsung' => 0,
            ]
        ];

        // Other necessary data for the view
        $statuses = ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];
        $kecepatanOptions = ['Reguler', 'Express', 'Kilat'];
        $metodeOptions = ['Antar Jemput', 'Datang Langsung', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri'];

        return view('pemesanan.create', compact(
            'layanans',
            'statuses',
            'kecepatanOptions',
            'metodeOptions',
            'biayaTambahan' // Pass additional costs to the view
        ));
    }
    /**
     * Store a newly created order in storage (ADMIN).
     * Logika ini jika Admin input semua data dari awal.
     */
    public function store(Request $request)
    {
        // Validasi disesuaikan dengan field baru
        $validatedData = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kontak_pelanggan' => 'required|string|max:100',
            'alamat_pelanggan' => 'nullable|string|max:1000', // Nullable jika diantar sendiri
            'layanan_utama_id' => 'required|exists:layanans,id',
            'kecepatan_layanan' => 'required|string|max:50', // Misal: Reguler, Express
            'metode_layanan' => 'required|string|max:100', // Misal: Antar Jemput, Datang Langsung
            'estimasi_berat' => 'nullable|numeric|min:0',
            'daftar_item' => 'nullable|string',
            'catatan_pelanggan' => 'nullable|string',
            'tanggal_penjemputan' => 'nullable|date|required_if:metode_layanan,Antar Jemput', // Contoh validasi kondisional
            'waktu_penjemputan' => 'nullable|string|max:50|required_if:metode_layanan,Antar Jemput',
            'instruksi_alamat' => 'nullable|string',
            'status_pesanan' => 'required|string|max:100', // Admin set status awal
            'berat_final' => 'nullable|numeric|min:0', // Admin bisa input langsung
            'total_harga' => 'nullable|numeric|min:0', // Admin bisa input langsung
            'kode_promo' => 'nullable|string|max:50',
            'tanggal_selesai' => 'nullable|date',
        ]);

        // Generate Nomor Pesanan Otomatis
        $noPesanan = 'ADM-' . now()->format('ymd') . strtoupper(Str::random(4));
        while (Pemesanan::where('no_pesanan', $noPesanan)->exists()) {
            $noPesanan = 'ADM-' . now()->format('ymd') . strtoupper(Str::random(5));
        }
        $validatedData['no_pesanan'] = $noPesanan;
        $validatedData['tanggal_pesan'] = now(); // Set tanggal pesan saat ini

        Pemesanan::create($validatedData);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan berhasil ditambahkan oleh Admin.');
    }


    // ======================================================
    // == METHOD UNTUK GUEST / PELANGGAN ==
    // ======================================================

    public function showGuestOrderForm()
    {
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan', 'harga']);

        // Definisikan biaya tambahan di sini agar bisa di-pass ke view
        // Ini bisa juga diambil dari database atau config jika lebih dinamis
        $biayaTambahan = [
            'kecepatan' => [
                'Reguler' => 0,
                'Express' => 10000, // Contoh biaya
                'Kilat' => 20000,   // Contoh biaya
            ],
            'metode' => [
                'Datang Langsung' => 0,
                'Antar Jemput' => 5000,                 // Contoh biaya
                'Antar Sendiri Minta Diantar' => 3000,  // Contoh biaya
                'Minta Dijemput Ambil Sendiri' => 3000, // Contoh biaya
            ]
        ];

        return view('guest.order_form', compact('layanans', 'biayaTambahan'));
    }

    public function storeGuestOrder(Request $request)
    {
        // 1. Validasi input dari guest sesuai form baru
        $validatedData = $request->validate([
            'nama_pelanggan'    => 'required|string|max:255',
            'kontak_pelanggan'  => 'required|string|max:100',
            'alamat_pelanggan'  => [
                Rule::requiredIf(function () use ($request) {
                    return in_array($request->input('metode_layanan'), ['Antar Jemput', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri']);
                }),
                'nullable',
                'string',
                'max:1000'
            ],
            'metode_layanan'    => 'required|string|in:Datang Langsung,Antar Jemput,Antar Sendiri Minta Diantar,Minta Dijemput Ambil Sendiri',
            'layanan_utama_id'  => 'required|exists:layanans,id',
            'kecepatan_layanan' => 'required|string|in:Reguler,Express,Kilat',
            'estimasi_berat'    => [
                'nullable',
                'numeric',
                'min:0',
                Rule::requiredIf(function () use ($request) {
                    $layananId = $request->input('layanan_utama_id');
                    if (!$layananId) {
                        return false; // If no layanan_id, don't require estimasi_berat
                    }
                    $layanan = Layanan::find($layananId);
                    if (!$layanan) {
                        return false; // If layanan not found, don't require
                    }
                    // Infer type: if 'nama_layanan' contains 'kiloan' (case-insensitive)
                    return Str::contains(strtolower($layanan->nama_layanan), 'kiloan');
                }),
            ],
            'daftar_item'       => [
                'nullable',
                'string',
                Rule::requiredIf(function () use ($request) {
                    $layananId = $request->input('layanan_utama_id');
                    if (!$layananId) {
                        return false; // If no layanan_id, don't require daftar_item
                    }
                    $layanan = Layanan::find($layananId);
                    if (!$layanan) {
                        return false; // If layanan not found, don't require
                    }
                    // Infer type: if 'nama_layanan' does NOT contain 'kiloan', assume it's satuan for this rule
                    return !Str::contains(strtolower($layanan->nama_layanan), 'kiloan');
                }),
            ],
            'catatan_pelanggan' => 'nullable|string|max:1000',
            'tanggal_penjemputan' => [
                'nullable',
                'date',
                'after_or_equal:today',
                Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Dijemput Ambil Sendiri']))
            ],
            'waktu_penjemputan' => [
                'nullable',
                'string',
                'max:50',
                Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Dijemput Ambil Sendiri']))
            ],
            'instruksi_alamat'  => 'nullable|string|max:1000',
            'kode_promo'        => 'nullable|string|max:50',
        ], [
            // Custom messages for standard rules
            'nama_pelanggan.required'    => 'Nama Anda wajib diisi.',
            'kontak_pelanggan.required'  => 'Nomor kontak wajib diisi.',
            'metode_layanan.required'    => 'Silakan pilih metode layanan.',
            'alamat_pelanggan.required'  => 'Alamat wajib diisi jika memilih layanan antar/jemput.',
            'layanan_utama_id.required'  => 'Silakan pilih jenis layanan utama.',
            'kecepatan_layanan.required' => 'Silakan pilih kecepatan layanan.',
            'tanggal_penjemputan.required' => 'Tanggal penjemputan wajib diisi jika minta dijemput.',
            'tanggal_penjemputan.after_or_equal' => 'Tanggal penjemputan tidak boleh tanggal yang sudah lalu.',
            'waktu_penjemputan.required'   => 'Waktu penjemputan wajib diisi jika minta dijemput.',
            // Custom messages for the conditional fields (these will be shown if the Rule::requiredIf evaluates to true and the field is empty)
            'estimasi_berat.required' => 'Estimasi berat wajib diisi untuk layanan kiloan.',
            'daftar_item.required' => 'Daftar item wajib diisi untuk layanan satuan.',
        ]);

        // 2. Generate Nomor Pesanan Otomatis
        $noPesanan = 'LNDRY-' . now()->format('ymd') . strtoupper(Str::random(4));
        while (Pemesanan::where('no_pesanan', $noPesanan)->exists()) {
            $noPesanan = 'LNDRY-' . now()->format('ymd') . strtoupper(Str::random(5));
        }

        // 3. Siapkan data lengkap untuk disimpan
        $orderData = $validatedData;
        $orderData['no_pesanan']     = $noPesanan;
        $orderData['tanggal_pesan']  = now();
        $orderData['status_pesanan'] = 'Baru'; // Status awal
        $orderData['berat_final']    = null;   // Admin akan mengisi ini nanti
        $orderData['total_harga']    = 0;      // Admin akan mengisi ini nanti

        // 4. Simpan ke database
        try {
            $pemesanan = Pemesanan::create($orderData);

            // 5. Beri feedback
            return redirect()->route('guest.order.form')
                ->with('swal_success_message', 'Pesanan Anda berhasil dibuat!')
                ->with('order_number', $pemesanan->no_pesanan);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pesanan guest: ' . $e->getMessage() . ' Data: ' . json_encode($orderData));
            return redirect()->route('guest.order.form')
                ->withInput() // Kembalikan input lama
                ->with('error', 'Terjadi kesalahan. Pesanan Anda gagal disimpan. Silakan coba lagi atau hubungi kami.');
        }
    }

    // ======================================================
    // == METHOD ADMIN LAINNYA (EDIT, UPDATE, DELETE) ==
    // ======================================================

    /**
     * Show the form for editing the specified order (ADMIN).
     * Pastikan view 'pemesanan.edit' diupdate untuk field baru.
     */
    public function edit($id)
    {
        $pemesanan = Pemesanan::with('layananUtama')->findOrFail($id);

        $layanans  = Layanan::orderBy('nama_layanan', 'asc')
            ->get(['id', 'nama_layanan', 'harga']);

        $biayaTambahan = [
            'kecepatan' => [
                'Reguler' => 0,
                'Express' => 10000,
                'Kilat' => 20000,
            ],
            'metode' => [
                'Antar Jemput' => 5000,
                'Antar Sendiri Minta Diantar' => 3000,
                'Minta Dijemput Ambil Sendiri' => 3000,
                'Datang Langsung' => 0,
            ]
        ];

        // Keep existing options
        $statuses = ['Baru', 'Menunggu Dijemput', 'Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];
        $kecepatanOptions = array_keys($biayaTambahan['kecepatan']);
        $metodeOptions = array_keys($biayaTambahan['metode']);
        return view('pemesanan.edit', compact(
            'pemesanan',
            'layanans',
            'statuses',
            'kecepatanOptions',
            'metodeOptions',
            'biayaTambahan'
        ));
    }

    /**
     * Update the specified order in storage (ADMIN).
     */
    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Validation rules for update
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
            // Fields updated by Admin
            'berat_final' => 'nullable|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'status_pesanan' => 'required|string|max:100',
            'kode_promo' => 'nullable|string|max:50',
            'tanggal_selesai' => 'nullable|date',
        ], [
            // Custom validation messages if needed
            'total_harga.required' => 'Total harga (otomatis) gagal dihitung atau kosong.',
            'total_harga.numeric' => 'Total harga harus berupa angka.',
        ]);


        // Logic to set tanggal_selesai automatically if status is 'Selesai' etc. and it's not already set
        if (
            in_array($validatedData['status_pesanan'], ['Selesai', 'Diambil', 'Sudah Diantar']) &&
            is_null($pemesanan->tanggal_selesai) &&
            empty($validatedData['tanggal_selesai'])
        ) {
            $validatedData['tanggal_selesai'] = now();
        }

        // Update the Pemesanan record
        $pemesanan->update($validatedData);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan (No: ' . $pemesanan->no_pesanan . ') berhasil diperbarui.');
    }


    /**
     * Remove multiple orders from storage (ADMIN).
     * (Tidak perlu perubahan logika inti, hanya pastikan route & view benar)
     */
    public function destroy(Request $request) // Untuk Bulk Delete
    {
        $ids = $request->input('ids');

        if (!empty($ids) && is_array($ids)) {
            $deletedCount = Pemesanan::whereIn('id', $ids)->delete();

            // Beri response JSON untuk AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $deletedCount . ' Pemesanan berhasil dihapus.'
                ]);
            }
            // Fallback redirect jika bukan AJAX (jarang terjadi dari JS)
            return redirect()->route('pemesanan.index')
                ->with('success', $deletedCount . ' Pemesanan berhasil dihapus.');
        }

        $errorMessage = 'Tidak ada pesanan yang dipilih atau format ID salah.';
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $errorMessage], 400);
        }
        return redirect()->route('pemesanan.index')->with('error', $errorMessage);
    }

    public function destroySingle(Request $request, $id) // Tambahkan Request $request
    {
        $pemesanan = Pemesanan::find($id);

        if ($pemesanan) {
            $pemesanan->delete();
            // Selalu kembalikan JSON karena ini dipanggil via AJAX
            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dihapus.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pesanan tidak ditemukan.'
        ], 404);
    }
}
