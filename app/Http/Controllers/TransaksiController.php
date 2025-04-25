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
        $pemesanans = Pemesanan::with('layanan')->get();
        return view('transaksi.create', compact('pemesanans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pemesanan_id' => 'required|exists:pemesanans,id',
            'dibayar'      => 'required|numeric|min:0',
        ]);

        $pemesanan          = Pemesanan::with('layanan')->findOrFail($data['pemesanan_id']);
        $data['layanan_id'] = $pemesanan->layanan_id;                 // auto–copy
        $hargaTotal         = $pemesanan->berat_pesanan * $pemesanan->layanan->harga;

        $transaksi = Transaksi::create([
            'pemesanan_id' => $pemesanan->id,
            'layanan_id'   => $pemesanan->layanan_id,
            'invoice'      => 'INV-' . now()->format('ymdHis'),
            'total_harga'  => $hargaTotal,       // simpan snapshot bila kolomnya ada
            'dibayar'      => $data['dibayar'],
        ]);

        return to_route('transaksis.index')->withSuccess('Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $transaksi = Transaksi::with(['pemesanan.layanan'])->findOrFail($id);
        return view('transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::with(['pemesanan.layanan'])->findOrFail($id);

        $request->validate([
            'dibayar' => 'required|numeric|min:0',
        ]);

        $transaksi->dibayar = $request->dibayar;
        $transaksi->save();

        return to_route('transaksis.index')->withSuccess('Transaksi berhasil diperbarui.');
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
