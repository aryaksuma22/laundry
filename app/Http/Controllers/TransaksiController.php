<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pemesanan;
use App\Models\Layanan;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $sortOrder = $request->get('sortOrder', 'asc');
        $sortBy    = $request->get('sortBy', 'id');
        $perPage   = $request->get('perPage', 10);
        $search    = $request->get('search', '');

        // kolom-kolom yang boleh di-sort
        $allowedSort = [
            'id',
            'invoice',
            'nama_pelanggan',    // via relasi pemesanan
            'nama_layanan',
            'berat_pesanan',
            'total_harga',      // via relasi layanan
        ];
        if (! in_array($sortBy, $allowedSort)) {
            $sortBy = 'id';
        }

        // bangun query dengan eager-load relasi
        $query = Transaksi::with(['pemesanan', 'layanan']);

        // search di kolom lokal dan kolom relasi
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'like', "%{$search}%")
                    ->orWhereHas('pemesanan', function ($q) use ($search) {
                        $q->where('berat_pesanan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pemesanan', function ($q) use ($search) {
                        $q->where('total_harga', 'like', "%{$search}%");
                    })
                    ->orWhere('dibayar', 'like', "%{$search}%")
                    ->orWhereHas('pemesanan', function ($q) use ($search) {
                        $q->where('nama_pelanggan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('layanan', function ($q) use ($search) {
                        $q->where('nama_layanan', 'like', "%{$search}%");
                    });
            });
        }


        $transaksis = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // jika request AJAX (pagination / search / sort)
        if ($request->ajax() && (
            $request->has('page')
            || $request->has('search')
            || $request->has('sortBy')
            || $request->has('sortOrder')
            || $request->has('perPage')
        )) {
            $html = view('transaksi.partials.table', compact('transaksis'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];

        return view('transaksi.index', compact(
            'transaksis',
            'search',
            'sortBy',
            'sortOrder',
            'perPage'
        ));
    }

    public function create()
    {
        $pemesanans = Pemesanan::all();   // untuk dropdown pilih pemesanan (nama_pelanggan)
        $layanans   = Layanan::all();     // untuk dropdown pilih layanan

        return view('transaksi.create', compact('pemesanans', 'layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemesanan_id'   => 'required|exists:pemesanans,id',
            'layanan_id'     => 'required|exists:layanans,id',
            'berat_pesanan'  => 'required|integer|min:1',
            'dibayar'        => 'required|numeric|min:0',
        ], [
            'pemesanan_id.required'  => 'Pemesanan wajib dipilih.',
            'pemesanan_id.exists'    => 'Pemesanan tidak valid.',
            'layanan_id.required'    => 'Layanan wajib dipilih.',
            'layanan_id.exists'      => 'Layanan tidak valid.',
            'berat_pesanan.required' => 'Berat pesanan wajib diisi.',
            'berat_pesanan.integer'  => 'Berat harus angka bulat.',
            'berat_pesanan.min'      => 'Berat minimal 1.',
            'dibayar.required'       => 'Jumlah dibayar wajib diisi.',
            'dibayar.numeric'        => 'Dibayar harus angka.',
            'dibayar.min'            => 'Dibayar tidak boleh negatif.',
        ]);

        $transaksi = new Transaksi();
        $transaksi->pemesanan_id  = $request->pemesanan_id;
        $transaksi->layanan_id    = $request->layanan_id;
        $transaksi->invoice       = 'INV-' . time(); // contoh generate, sesuaikan bila perlu
        $transaksi->berat_pesanan = $request->berat_pesanan;

        // hitung total_harga berdasarkan berat × harga layanan
        $layanan = Layanan::find($request->layanan_id);
        $transaksi->total_harga = $layanan->harga * $request->berat_pesanan;

        $transaksi->dibayar = $request->dibayar;
        $transaksi->save();

        return redirect()->route('transaksis.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $transaksi  = Transaksi::findOrFail($id);
        $pemesanans = Pemesanan::all();
        $layanans   = Layanan::all();

        return view('transaksi.edit', compact('transaksi', 'pemesanans', 'layanans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pemesanan_id'   => 'required|exists:pemesanans,id',
            'layanan_id'     => 'required|exists:layanans,id',
            'berat_pesanan'  => 'required|integer|min:1',
            'dibayar'        => 'required|numeric|min:0',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->pemesanan_id  = $request->pemesanan_id;
        $transaksi->layanan_id    = $request->layanan_id;
        $transaksi->berat_pesanan = $request->berat_pesanan;

        $layanan = Layanan::find($request->layanan_id);
        $transaksi->total_harga = $layanan->harga * $request->berat_pesanan;

        $transaksi->dibayar = $request->dibayar;
        $transaksi->save();

        return redirect()->route('transaksis.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('transaksis', []);

        if (count($ids)) {
            Transaksi::whereIn('id', $ids)->delete();
            $msg = 'Transaksi berhasil dihapus.';
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->route('transaksis.index')->with('success', $msg);
        }

        $err = 'Tidak ada transaksi yang dipilih.';
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $err], 400);
        }
        return redirect()->route('transaksis.index')->with('error', $err);
    }

    public function destroySingle($id)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            $transaksi->delete();
            return response()->json(['success' => true, 'message' => 'Transaksi dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.'], 404);
    }
}
