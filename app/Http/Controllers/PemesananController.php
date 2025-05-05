<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PemesananController extends Controller
{
    /**
     * Display a listing of the orders for ADMIN.
     */
    public function index(Request $request)
    {
        $sortOrder = $request->get('sortOrder', 'asc');
        $sortBy    = $request->get('sortBy', 'nama_pelanggan');
        $perPage = $request->get('perPage', 10);
        $search  = $request->get('search', '');
        $allowedSort = [
            'nama_pelanggan',
            'no_pesanan',
            'tanggal',
            'berat_pesanan',
            'total_harga',
            'status_pesanan',
            'alamat',
            'kontak'
        ];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama_pelanggan';
        }

        $query = Pemesanan::with(['layanan']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_pesanan', 'like', "%{$search}%")
                    ->orWhere('tanggal', 'like', "%{$search}%")
                    ->orWhereHas('layanan', function ($subq) use ($search) {
                        $subq->where('nama_layanan', 'like', "%{$search}%");
                    })
                    ->orWhere('berat_pesanan', 'like', "%{$search}%")
                    ->orWhere('total_harga', 'like', "%{$search}%")
                    ->orWhere('status_pesanan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%");
            });
        }

        // Apply sorting and paginate
        $pemesanans = $query->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        // Handle AJAX
        if ($request->ajax() && (
            $request->has('page') || $request->has('search') || $request->has('sortBy') ||
            $request->has('sortOrder') || $request->has('perPage')
        )) {
            $html = view('pemesanan.partials.table', compact('pemesanans'))->render();
            return response()->json(['html' => $html]);
        }

        return view('pemesanan.index', compact(
            'pemesanans',
            'search',
            'sortBy',
            'sortOrder',
            'perPage'
        ));
    }

    /**
     * Show the form for creating a new order (ADMIN).
     * Jika admin masih perlu membuat pesanan dari nol.
     */
    public function create()
    {
        $layanans = Layanan::all();
        return view('pemesanan.create', compact('layanans'));
    }

    /**
     * Store a newly created order in storage (ADMIN).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_pesanan'     => 'required|string|unique:pemesanans,no_pesanan',
            'tanggal'        => 'required|date',
            'layanan_id'     => 'required|exists:layanans,id',
            'berat_pesanan'  => 'required|numeric|min:0',
            'total_harga'    => 'required|numeric|min:0',
            'status_pesanan' => 'required|string|max:100',
            'alamat'         => 'required|string|max:500',
            'kontak'         => 'required|string|max:100',
        ], [
            'no_pesanan.unique' => 'Nomor pesanan ini sudah digunakan.',
        ]);

        Pemesanan::create($request->all());

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan berhasil ditambahkan oleh Admin.');
    }


    // ======================================================
    // == METHOD BARU UNTUK GUEST / PELANGGAN ==
    // ======================================================

    /**
     * Show the form for GUEST to create a new order.
     * Dipanggil oleh route GET /buat-pesanan
     */
    public function showGuestOrderForm()
    {
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get(['id', 'nama_layanan', 'harga']);
        return view('guest.order_form', compact('layanans'));
    }

    /**
     * Store a newly created order from GUEST form.
     */
    public function storeGuestOrder(Request $request)
    {
        // 1. Validasi input dari guest
        $validatedData = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'layanan_id'     => 'required|exists:layanans,id',
            'alamat'         => 'required|string|max:500',
            'kontak'         => 'required|string|max:100',
        ], [
            'nama_pelanggan.required' => 'Nama Anda wajib diisi.',
            'layanan_id.required'     => 'Silakan pilih jenis layanan.',
            'layanan_id.exists'       => 'Jenis layanan tidak valid.',
            'alamat.required'         => 'Alamat wajib diisi.',
            'kontak.required'         => 'Nomor kontak wajib diisi.',
        ]); 

        // 2. Generate Nomor Pesanan Otomatis (contoh)
        $noPesanan = 'LNDRY-' . now()->format('ymd') . strtoupper(Str::random(4));
        while (Pemesanan::where('no_pesanan', $noPesanan)->exists()) {
            $noPesanan = 'LNDRY-' . now()->format('ymd') . strtoupper(Str::random(5));
        }

        // 3. Siapkan data untuk disimpan
        $orderData = $validatedData;
        $orderData['no_pesanan']     = $noPesanan;
        $orderData['tanggal']        = now();
        $orderData['status_pesanan'] = 'Menunggu';
        $orderData['berat_pesanan']  = 0;
        $orderData['total_harga']    = 0;

        // 4. Simpan ke database
        try {
            $pemesanan = Pemesanan::create($orderData);

            // 5. Beri feedback ke Guest via SweetAlert
            // Kirim data spesifik untuk Swal ke session
            return redirect()->route('guest.order.form')
                ->with('swal_success_message', 'Pesanan Anda berhasil dibuat!') // Pesan untuk Swal
                ->with('order_number', $pemesanan->no_pesanan);             // Nomor pesanan untuk Swal

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pesanan guest: ' . $e->getMessage());
            return redirect()->route('guest.order.form')
                ->withInput()
                ->with('error', 'Terjadi kesalahan. Pesanan Anda gagal disimpan. Silakan coba lagi.'); // Error biasa jika gagal
        }
    }

    // ======================================================
    // == METHOD ADMIN LAINNYA (EDIT, UPDATE, DELETE) ==
    // ======================================================

    /**
     * Show the form for editing the specified order (ADMIN).
     */
    public function edit($id)
    {
        $pemesanan = Pemesanan::with('layanan')->findOrFail($id);
        $layanans  = Layanan::all(['id', 'nama_layanan', 'harga']);
        return view('pemesanan.edit', compact('pemesanan', 'layanans'));
    }

    /**
     * Update the specified order in storage (ADMIN).
     * Tempat Admin mengisi Berat, Harga, dan mengubah Status.
     */
    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        $data = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_pesanan'     => ['required', 'string', Rule::unique('pemesanans', 'no_pesanan')->ignore($id)],
            'layanan_id'     => 'required|exists:layanans,id',
            'tanggal'        => 'required|date',
            'berat_pesanan'  => 'required|numeric|min:0',
            'status_pesanan' => 'required|string|max:100',
            'alamat'         => 'required|string|max:500',
            'kontak'         => 'required|string|max:100',
        ]);

        // Hitung ulang Total Harga berdasarkan layanan dan berat
        // Asumsi: $layanan->harga adalah harga per Kg (atau per satuan berat yang diinput)
        try {
            $layanan = Layanan::findOrFail($data['layanan_id']);
            // Pastikan harga ada dan berat adalah angka
            if (isset($layanan->harga) && is_numeric($data['berat_pesanan'])) {
                // Lakukan perhitungan
                $calculatedPrice = $layanan->harga * (float)$data['berat_pesanan'];
                $data['total_harga'] = $calculatedPrice; // Simpan harga hasil perhitungan
            } else {
                // Jika tidak bisa dihitung (misal layanan tidak ditemukan atau berat tidak valid)
                // Anda bisa:
                // 1. Melempar error
                // throw new \Exception("Tidak bisa menghitung harga.");
                // 2. Menggunakan nilai dari input (jika admin bisa input manual)
                $data['total_harga'] = $request->input('total_harga', $pemesanan->total_harga); // Ambil dari input form atau pertahankan yg lama
                // 3. Set ke 0 atau null
                // $data['total_harga'] = 0;
                Log::warning("Tidak dapat menghitung total harga untuk pesanan ID: {$id}. Layanan ID: {$data['layanan_id']}, Berat: {$data['berat_pesanan']}");
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Layanan dengan ID {$data['layanan_id']} tidak ditemukan saat update pesanan ID: {$id}");
            // Handle error - mungkin kembalikan ke form dengan pesan error
            return back()->withInput()->with('error', 'Layanan yang dipilih tidak valid.');
        }


        // Logika update status otomatis ke 'Proses' jika sebelumnya 'Menunggu' dan berat sudah diisi
        if ($pemesanan->status_pesanan === 'Menunggu' && $data['status_pesanan'] === 'Menunggu') {
            // Cek jika berat diisi (lebih dari 0)
            if (isset($data['berat_pesanan']) && (float)$data['berat_pesanan'] > 0) {
                $data['status_pesanan'] = 'Proses'; // Otomatis ubah ke Proses
            }
        }
        // Jika admin memilih status lain secara manual, $data['status_pesanan'] akan menimpanya.


        // Update data pemesanan di database
        $pemesanan->update($data);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan (No: ' . $pemesanan->no_pesanan . ') berhasil diperbarui.');
    }


    /**
     * Remove multiple orders from storage (ADMIN).
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids) && is_array($ids)) {
            $deletedCount = Pemesanan::whereIn('id', $ids)->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $deletedCount . ' Pemesanan berhasil dihapus.'
                ]);
            }

            return redirect()->route('pemesanan.index')
                ->with('success', $deletedCount . ' Pemesanan berhasil dihapus.');
        }

        $errorMessage = 'Tidak ada pesanan yang dipilih atau format ID salah.';
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $errorMessage], 400);
        }
        return redirect()->route('pemesanan.index')->with('error', $errorMessage);
    }

    /**
     * Remove a single order via AJAX (ADMIN).
     */
    public function destroySingle($id)
    {
        $pemesanan = Pemesanan::find($id);

        if ($pemesanan) {
            $pemesanan->delete();
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
