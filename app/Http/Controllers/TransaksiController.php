<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pemesanan; // Kita mungkin perlu ini untuk filter atau data terkait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // Jika diperlukan untuk validasi enum/string terbatas

class TransaksiController extends Controller
{
    /**
     * Display a listing of the transactions for ADMIN.
     */
    public function index(Request $request)
    {
        // --- Pagination/Sort/Search Params ---
        $sortOrder = $request->get('sortOrder', 'desc');
        $sortBy    = $request->get('sortBy', 'created_at'); // Default sort untuk transaksi
        $perPage   = $request->get('perPage', 10);
        $search    = $request->get('search', '');

        // --- Filter Params (Sesuaikan dengan kebutuhan transaksi) ---
        $filterStatusPembayaran = $request->get('filter_status_pembayaran', '');
        $filterMetodePembayaran = $request->get('filter_metode_pembayaran', '');
        // Anda bisa menambahkan filter lain seperti rentang tanggal

        // --- Allowed Sort Columns (Sesuaikan dengan kolom di tabel 'transaksis') ---
        $allowedSort = ['created_at', 'no_invoice', 'jumlah_dibayar', 'status_pembayaran', 'metode_pembayaran', 'tanggal_pembayaran'];
        // Tambahkan 'pemesanan.no_pesanan' jika ingin sort by nomor pesanan terkait
        // $allowedSort[] = 'pemesanans.no_pesanan'; // Contoh jika join
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'created_at';
        }

        // --- Build the Query ---
        // Eager load pemesanan untuk menampilkan info terkait (misal, no_pesanan, nama_pelanggan)
        $query = Transaksi::with(['pemesanan']); // Eager load pemesanan

        // Apply Search Filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                    ->orWhere('jumlah_dibayar', 'like', "%{$search}%") // Mungkin perlu penyesuaian jika search angka
                    ->orWhere('status_pembayaran', 'like', "%{$search}%")
                    ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                    ->orWhere('tanggal_pembayaran', 'like', "%{$search}%") // Search tanggal
                    ->orWhereHas('pemesanan', function ($subq) use ($search) { // Search di data pemesanan terkait
                        $subq->where('no_pesanan', 'like', "%{$search}%")
                            ->orWhere('nama_pelanggan', 'like', "%{$search}%");
                    });
            });
        }

        // --- Apply Dropdown Filters ---
        $query->when($filterStatusPembayaran, function ($q, $status) {
            return $q->where('status_pembayaran', $status);
        });

        $query->when($filterMetodePembayaran, function ($q, $metode) {
            return $q->where('metode_pembayaran', $metode);
        });
        // --- End Filter Application ---

        // --- Handling Sort by Related Table Column (Contoh: Nomor Pesanan) ---
        // if ($sortBy === 'pemesanans.no_pesanan') {
        //     $query->join('pemesanans', 'transaksis.pemesanan_id', '=', 'pemesanans.id')
        //           ->orderBy('pemesanans.no_pesanan', $sortOrder)
        //           ->select('transaksis.*'); // Penting untuk select kolom dari transaksis agar tidak bentrok
        // } else {
        $query->orderBy($sortBy, $sortOrder);
        // }

        $transaksis = $query->paginate($perPage);

        // --- Data for Filters (Hanya untuk full page load) ---
        $filterData = [];
        if (!$request->ajax() || !$request->hasAny(['page', 'search', 'sortBy', 'sortOrder', 'perPage', 'filter_status_pembayaran', 'filter_metode_pembayaran'])) {
            // Ambil opsi unik dari database atau definisikan secara manual
            $filterData['statusOptions'] = Transaksi::query()->distinct()->pluck('status_pembayaran')->sort()->toArray();
            // Jika Anda ingin urutan tertentu atau pilihan tetap:
            // $filterData['statusOptions'] = ['Belum Lunas', 'Lunas'];
            $filterData['metodeOptions'] = Transaksi::query()->whereNotNull('metode_pembayaran')->distinct()->pluck('metode_pembayaran')->sort()->toArray();
        }

        // Handle AJAX request for table update
        if ($request->ajax() && $request->hasAny(['page', 'search', 'sortBy', 'sortOrder', 'perPage', 'filter_status_pembayaran', 'filter_metode_pembayaran'])) {
            Log::info('AJAX request for Transaksi TABLE update detected.');
            try {
                // Pastikan nama partial view benar: 'transaksi.partials.table'
                $html = view('transaksi.partials.table', compact('transaksis'))->render();
                return response()->json(['html' => $html]);
            } catch (\Exception $e) {
                Log::error('Error rendering transaksi partial table: ' . $e->getMessage());
                return response()->json(['error' => 'Gagal memuat tabel transaksi.'], 500);
            }
        }

        // Render Full View
        Log::info('Full page request for Transaksi index.');
        return view('transaksi.index', compact(
            'transaksis',
            'search',
            'sortBy',
            'sortOrder',
            'perPage',
            'filterStatusPembayaran',
            'filterMetodePembayaran',
            'filterData'
        ));
    }

    /**
     * Show the form for creating a new transaction.
     * Biasanya, transaksi dibuat terkait dengan sebuah pemesanan.
     * Jadi, form ini mungkin lebih cocok dipanggil dari halaman detail/edit pemesanan,
     * atau memiliki cara untuk memilih pemesanan yang akan dibayar.
     * Untuk contoh ini, kita asumsikan ada route terpisah.
     */
    public function create(Request $request)
    {
        // Ambil pemesanan_id dari request jika ada (misal, dari tombol "Bayar" di halaman pemesanan)
        $pemesananId = $request->query('pemesanan_id');
        $pemesanan = null;
        $existingTransaksi = null;

        if ($pemesananId) {
            $pemesanan = Pemesanan::with('transaksi')->find($pemesananId);
            if ($pemesanan && $pemesanan->transaksi) {
                // Jika sudah ada transaksi, mungkin lebih baik redirect ke edit transaksi tersebut
                // atau tampilkan pesan bahwa transaksi sudah ada.
                // Untuk contoh ini, kita biarkan flow create baru, tapi data lama bisa jadi referensi
                $existingTransaksi = $pemesanan->transaksi;
                // Jika transaksi sudah lunas, mungkin blokir pembuatan transaksi baru atau redirect
                // if ($existingTransaksi && $existingTransaksi->isLunas()) {
                //     return redirect()->route('transaksi.edit', $existingTransaksi->id)->with('info', 'Pesanan ini sudah lunas.');
                // }
            }
        }

        // Opsi untuk dropdown
        $statusPembayaranOptions = ['Belum Lunas', 'Lunas'];
        $metodePembayaranOptions = ['Tunai', 'Transfer Bank BCA', 'Transfer Bank Mandiri', 'QRIS', 'GoPay', 'OVO', 'Lainnya']; // Sesuaikan

        // Jika tidak ada pemesanan_id, admin mungkin perlu memilih dari daftar pemesanan yang belum lunas
        $pemesanansBelumLunas = Pemesanan::whereDoesntHave('transaksi', function ($query) {
            $query->where('status_pembayaran', 'Lunas');
        })
            ->orWhereHas('transaksi', function ($query) {
                $query->where('status_pembayaran', 'Belum Lunas');
            })
            ->orderBy('tanggal_pesan', 'desc')
            ->get(['id', 'no_pesanan', 'nama_pelanggan', 'total_harga']);

        // Pastikan view 'transaksi.create' sudah dibuat
        return view('transaksi.create', compact(
            'pemesanan', // Pemesanan yang dipilih (jika ada)
            'existingTransaksi', // Transaksi yang sudah ada untuk pemesanan ini (jika ada)
            'statusPembayaranOptions',
            'metodePembayaranOptions',
            'pemesanansBelumLunas' // Daftar pemesanan yang bisa dipilih
        ));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pemesanan_id' => 'required|exists:pemesanans,id|unique:transaksis,pemesanan_id', // Pastikan belum ada transaksi untuk pemesanan ini
            'no_invoice' => 'nullable|string|max:255',
            'jumlah_dibayar' => 'required|numeric|min:0',
            'status_pembayaran' => ['required', 'string', Rule::in(['Belum Lunas', 'Lunas'])],
            'metode_pembayaran' => 'nullable|string|max:100|required_if:status_pembayaran,Lunas', // Wajib jika lunas
            'tanggal_pembayaran' => 'nullable|date|required_if:status_pembayaran,Lunas', // Wajib jika lunas
            'catatan_transaksi' => 'nullable|string',
        ], [
            'pemesanan_id.unique' => 'Pemesanan ini sudah memiliki data transaksi. Harap edit transaksi yang ada.',
            'metode_pembayaran.required_if' => 'Metode pembayaran wajib diisi jika status lunas.',
            'tanggal_pembayaran.required_if' => 'Tanggal pembayaran wajib diisi jika status lunas.',
        ]);

        // Ambil total harga dari pemesanan terkait
        $pemesanan = Pemesanan::find($validatedData['pemesanan_id']);
        if (!$pemesanan) {
            return back()->with('error', 'Pemesanan tidak ditemukan.');
        }

        // Logika tambahan:
        // Jika status 'Lunas', pastikan jumlah_dibayar >= total_harga pesanan
        if ($validatedData['status_pembayaran'] === 'Lunas') {
            if ($validatedData['jumlah_dibayar'] < $pemesanan->total_harga) {
                // Boleh jadi error, atau otomatis set jumlah_dibayar = total_harga
                // Untuk sekarang, kita anggap admin sudah input benar.
                // Atau bisa tambahkan validasi:
                // 'jumlah_dibayar' => ['required', 'numeric', 'min:0', function ($attribute, $value, $fail) use ($pemesanan, $request) {
                //     if ($request->input('status_pembayaran') === 'Lunas' && $value < $pemesanan->total_harga) {
                //         $fail('Jumlah dibayar harus sama atau lebih besar dari total harga pesanan (Rp '.number_format($pemesanan->total_harga,0,',','.').') jika status Lunas.');
                //     }
                // }],
            }
            // Jika lunas dan tanggal pembayaran kosong, set ke sekarang
            if (empty($validatedData['tanggal_pembayaran'])) {
                $validatedData['tanggal_pembayaran'] = now();
            }
        } else { // Belum Lunas
            // Jika belum lunas, tanggal pembayaran bisa di-null-kan
            $validatedData['tanggal_pembayaran'] = null;
        }

        // Jika no_invoice kosong, bisa diisi dengan no_pesanan
        if (empty($validatedData['no_invoice']) && $pemesanan) {
            $validatedData['no_invoice'] = $pemesanan->no_pesanan;
        }

        Transaksi::create($validatedData);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified transaction.
     * (Opsional, jika Anda butuh halaman detail khusus transaksi)
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load('pemesanan.layananUtama'); // Load relasi yang mungkin dibutuhkan
        // Pastikan view 'transaksi.show' sudah dibuat
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('pemesanan'); // Load pemesanan terkait
        $pemesanan = $transaksi->pemesanan;

        $statusPembayaranOptions = ['Belum Lunas', 'Lunas'];
        $metodePembayaranOptions = ['Tunai', 'Transfer Bank BCA', 'Transfer Bank Mandiri', 'QRIS', 'GoPay', 'OVO', 'Lainnya']; // Sesuaikan

        // Pastikan view 'transaksi.edit' sudah dibuat
        return view('transaksi.edit', compact('transaksi', 'pemesanan', 'statusPembayaranOptions', 'metodePembayaranOptions'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validatedData = $request->validate([
            // pemesanan_id tidak diubah saat update transaksi
            'no_invoice' => 'nullable|string|max:255',
            'jumlah_dibayar' => 'required|numeric|min:0',
            'status_pembayaran' => ['required', 'string', Rule::in(['Belum Lunas', 'Lunas'])],
            'metode_pembayaran' => 'nullable|string|max:100|required_if:status_pembayaran,Lunas',
            'tanggal_pembayaran' => 'nullable|date|required_if:status_pembayaran,Lunas',
            'catatan_transaksi' => 'nullable|string',
        ], [
            'metode_pembayaran.required_if' => 'Metode pembayaran wajib diisi jika status lunas.',
            'tanggal_pembayaran.required_if' => 'Tanggal pembayaran wajib diisi jika status lunas.',
        ]);

        $pemesanan = $transaksi->pemesanan; // Ambil pemesanan dari transaksi yang diedit
        if (!$pemesanan) {
            // Ini seharusnya tidak terjadi jika relasi di DB benar
            return back()->with('error', 'Pemesanan terkait tidak ditemukan.');
        }

        // Logika tambahan seperti di store()
        if ($validatedData['status_pembayaran'] === 'Lunas') {
            if ($validatedData['jumlah_dibayar'] < $pemesanan->total_harga) {
                // Bisa tambahkan validasi seperti di store
            }
            if (empty($validatedData['tanggal_pembayaran'])) {
                $validatedData['tanggal_pembayaran'] = $transaksi->tanggal_pembayaran ?? now(); // Pertahankan tanggal lama jika ada, atau set baru
            }
        } else { // Belum Lunas
            $validatedData['tanggal_pembayaran'] = null;
        }


        if (empty($validatedData['no_invoice']) && $pemesanan) {
            $validatedData['no_invoice'] = $pemesanan->no_pesanan;
        }

        $transaksi->update($validatedData);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi (No Invoice: ' . $transaksi->no_invoice . ') berhasil diperbarui.');
    }

    /**
     * Remove multiple transactions from storage.
     */
    public function destroy(Request $request) // Untuk Bulk Delete
    {
        $ids = $request->input('ids');
        if (!empty($ids) && is_array($ids)) {
            $deletedCount = Transaksi::whereIn('id', $ids)->delete();
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $deletedCount . ' Transaksi berhasil dihapus.']);
            }
            return redirect()->route('transaksi.index')->with('success', $deletedCount . ' Transaksi berhasil dihapus.');
        }
        $errorMessage = 'Tidak ada transaksi yang dipilih atau format ID salah.';
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $errorMessage], 400);
        }
        return redirect()->route('transaksi.index')->with('error', $errorMessage);
    }

    /**
     * Remove a single transaction from storage.
     */
    public function destroySingle(Request $request, $id)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            $transaksi->delete();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.'], 404);
    }
}
