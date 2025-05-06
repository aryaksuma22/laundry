<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Layanan; // Pastikan model Layanan ada dan benar
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Tambahkan ini untuk validasi kondisional

class PemesananController extends Controller
{
    /**
     * Display a listing of the orders for ADMIN.
     */
    public function index(Request $request)
    {
        $sortOrder = $request->get('sortOrder', 'desc');
        $sortBy    = $request->get('sortBy', 'tanggal_pesan');
        $perPage = $request->get('perPage', 10);
        $search  = $request->get('search', '');

        $allowedSort = [
            'nama_pelanggan',
            'no_pesanan',
            'tanggal_pesan',
            'status_pesanan',
            'total_harga',
            'metode_layanan',
            'kontak_pelanggan'
        ];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'tanggal_pesan';
        }

        $query = Pemesanan::with(['layananUtama']);

        if (!empty($search)) {
            // Logika search (sudah oke)
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_pesanan', 'like', "%{$search}%")
                    ->orWhere('tanggal_pesan', 'like', "%{$search}%")
                    ->orWhereHas('layananUtama', function ($subq) use ($search) {
                        $subq->where('nama_layanan', 'like', "%{$search}%");
                    })
                    ->orWhere('status_pesanan', 'like', "%{$search}%")
                    ->orWhere('alamat_pelanggan', 'like', "%{$search}%")
                    ->orWhere('kontak_pelanggan', 'like', "%{$search}%");
            });
        }

        $pemesanans = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // ================== PERUBAHAN LOGIKA AJAX ==================
        // Hanya kembalikan JSON partial jika request AJAX DAN
        // merupakan request untuk update tabel (ada parameter spesifik)
        if ($request->ajax() && (
            $request->has('page') || $request->has('search') || $request->has('sortBy') ||
            $request->has('sortOrder') || $request->has('perPage')
        )) {
            // Ini adalah request untuk sorting, pagination, search via pemesanan.js
            Log::info('AJAX request for Pemesanan TABLE update detected.');
            try {
                $html = view('pemesanan.partials.table', compact('pemesanans'))->render();
                return response()->json(['html' => $html]);
            } catch (\Exception $e) {
                Log::error('Error rendering pemesanan partial table: ' . $e->getMessage());
                return response()->json(['error' => 'Gagal memuat tabel.'], 500);
            }
        }
        // ================== AKHIR PERUBAHAN ==================

        // Jika request BUKAN AJAX, ATAU request AJAX tapi dari sidebar (tidak ada param page/sort/dll)
        // Maka render view LENGKAP (index.blade.php)
        Log::info('Full page request (or initial AJAX load) for Pemesanan index.');
        return view('pemesanan.index', compact(
            'pemesanans', // Kirim data untuk render awal jika JS gagal
            'search',
            'sortBy',
            'sortOrder',
            'perPage'
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
        $statuses = ['Baru', 'Menunggu Dijemput','Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan'];
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

    /**
     * Show the form for GUEST to create a new order.
     * Pastikan view 'guest.order_form' memiliki field-field baru.
     */
    public function showGuestOrderForm()
    {
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan']); // Hanya perlu id dan nama
        // Anda mungkin perlu mengirim data lain ke view (misal: list kecepatan, metode layanan jika statis)
        return view('guest.order_form', compact('layanans'));
    }

    /**
     * Store a newly created order from GUEST form.
     */
    public function storeGuestOrder(Request $request)
    {
        // 1. Validasi input dari guest sesuai form baru
        $validatedData = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kontak_pelanggan' => 'required|string|max:100', // Ganti nama field
            // Alamat wajib jika metode layanan melibatkan penjemputan/pengantaran
            'alamat_pelanggan' => [
                Rule::requiredIf(function () use ($request) {
                    // Contoh: Wajib jika metode layanan adalah 'Antar Jemput' atau 'Minta Diantar' atau 'Minta Dijemput'
                    return in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Diantar', 'Minta Dijemput']);
                }),
                'nullable', // Boleh null jika tidak wajib
                'string',
                'max:1000'
            ],
            'layanan_utama_id' => 'required|exists:layanans,id', // Ganti nama field
            'kecepatan_layanan' => 'required|string|max:50', // Tambah validasi
            'metode_layanan' => 'required|string|max:100', // Tambah validasi
            'estimasi_berat' => 'nullable|numeric|min:0', // Tambah validasi
            'daftar_item' => 'nullable|string', // Tambah validasi
            'catatan_pelanggan' => 'nullable|string', // Tambah validasi
            // Tanggal & Waktu Jemput wajib jika metode melibatkan penjemputan
            'tanggal_penjemputan' => ['nullable', 'date', Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Dijemput']))],
            'waktu_penjemputan' => ['nullable', 'string', 'max:50', Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Dijemput']))],
            'instruksi_alamat' => 'nullable|string', // Tambah validasi
            'kode_promo' => 'nullable|string|max:50', // Tambah validasi
        ], [
            // Custom messages bisa ditambahkan di sini
            'nama_pelanggan.required' => 'Nama Anda wajib diisi.',
            'kontak_pelanggan.required' => 'Nomor kontak wajib diisi.',
            'alamat_pelanggan.required' => 'Alamat wajib diisi jika memilih layanan antar/jemput.',
            'layanan_utama_id.required' => 'Silakan pilih jenis layanan utama.',
            'kecepatan_layanan.required' => 'Silakan pilih kecepatan layanan.',
            'metode_layanan.required' => 'Silakan pilih metode layanan.',
            'tanggal_penjemputan.required' => 'Tanggal penjemputan wajib diisi jika minta dijemput.',
            'waktu_penjemputan.required' => 'Waktu penjemputan wajib diisi jika minta dijemput.',
        ]);

        // 2. Generate Nomor Pesanan Otomatis (sudah oke)
        $noPesanan = 'LNDRY-' . now()->format('ymd') . strtoupper(Str::random(4));
        while (Pemesanan::where('no_pesanan', $noPesanan)->exists()) {
            $noPesanan = 'LNDRY-' . now()->format('ymd') . strtoupper(Str::random(5));
        }

        // 3. Siapkan data lengkap untuk disimpan
        $orderData = $validatedData; // Ambil semua data yang sudah divalidasi
        $orderData['no_pesanan']     = $noPesanan;
        $orderData['tanggal_pesan']  = now(); // Gunakan field baru
        $orderData['status_pesanan'] = 'Baru'; // Status awal yang lebih jelas
        $orderData['berat_final']    = null; // Berat final belum ada
        $orderData['total_harga']    = 0; // Total harga belum final
        // Field lain sudah masuk dari $validatedData

        // 4. Simpan ke database
        try {
            $pemesanan = Pemesanan::create($orderData);

            // 5. Beri feedback (sudah oke, pastikan route 'guest.order.form' benar)
            return redirect()->route('guest.order.form') // Pastikan nama route ini benar
                ->with('swal_success_message', 'Pesanan Anda berhasil dibuat!')
                ->with('order_number', $pemesanan->no_pesanan);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pesanan guest: ' . $e->getMessage() . ' Data: ' . json_encode($orderData)); // Log data juga
            return redirect()->route('guest.order.form') // Pastikan nama route ini benar
                ->withInput() // Kembalikan input lama
                ->with('error', 'Terjadi kesalahan. Pesanan Anda gagal disimpan. Silakan coba lagi.');
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
        // Ganti relasi ke layananUtama
        $pemesanan = Pemesanan::with('layananUtama')->findOrFail($id);
        $layanans  = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan']);
        // Kirim data lain yang mungkin dibutuhkan di form edit
        // Misal: daftar status, daftar kecepatan, daftar metode
        $statuses = ['Baru', 'Menunggu Dijemput','Menunggu Diantar', 'Dijemput', 'Diproses', 'Siap Diantar', 'Siap Diambil', 'Selesai', 'Dibatalkan']; // Contoh
        $kecepatanOptions = ['Reguler', 'Express', 'Kilat']; // Contoh
        $metodeOptions = ['Antar Jemput', 'Datang Langsung', 'Antar Sendiri Minta Diantar', 'Minta Dijemput Ambil Sendiri']; // Contoh

        return view('pemesanan.edit', compact('pemesanan', 'layanans', 'statuses', 'kecepatanOptions', 'metodeOptions'));
    }

    /**
     * Update the specified order in storage (ADMIN).
     * Tempat Admin mengisi Berat Final, Harga Final, dan mengubah Status.
     */
    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Validasi semua field yang bisa diubah admin
        $validatedData = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kontak_pelanggan' => 'required|string|max:100',
            'alamat_pelanggan' => ['nullable', 'string', 'max:1000', Rule::requiredIf(fn() => in_array($request->input('metode_layanan'), ['Antar Jemput', 'Minta Diantar', 'Minta Dijemput']))],
            // no_pesanan mungkin tidak perlu diubah, jika perlu:
            // 'no_pesanan' => ['required', 'string', Rule::unique('pemesanans', 'no_pesanan')->ignore($id)],
            'layanan_utama_id' => 'required|exists:layanans,id',
            'kecepatan_layanan' => 'required|string|max:50',
            'metode_layanan' => 'required|string|max:100',
            'estimasi_berat' => 'nullable|numeric|min:0',
            'daftar_item' => 'nullable|string',
            'catatan_pelanggan' => 'nullable|string',
            'tanggal_penjemputan' => 'nullable|date',
            'waktu_penjemputan' => 'nullable|string|max:50',
            'instruksi_alamat' => 'nullable|string',
            // Field yang diisi/diupdate oleh Admin setelah verifikasi
            'berat_final' => 'nullable|numeric|min:0', // Berat aktual
            'total_harga' => 'nullable|numeric|min:0', // Harga final (input manual)
            'status_pesanan' => 'required|string|max:100', // Status diupdate admin
            'kode_promo' => 'nullable|string|max:50',
            'tanggal_selesai' => 'nullable|date', // Admin bisa set tanggal selesai
        ]);

        // Hapus logika kalkulasi harga otomatis & status otomatis.
        // Admin bertanggung jawab penuh mengisi berat_final, total_harga, dan status_pesanan
        // sesuai kondisi nyata.

        // Jika status diubah menjadi 'Selesai' atau status akhir lainnya, set tanggal_selesai
        if (in_array($validatedData['status_pesanan'], ['Selesai', 'Diambil', 'Sudah Diantar']) && is_null($pemesanan->tanggal_selesai)) {
            // Cek jika tanggal selesai belum diinput manual oleh admin
            if (empty($validatedData['tanggal_selesai'])) {
                $validatedData['tanggal_selesai'] = now(); // Set otomatis jika kosong dan status selesai
            }
        }

        // Update data pemesanan di database
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
