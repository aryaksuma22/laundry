<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pemesanan;
use App\Models\Layanan;

class LayananController extends Controller
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
            'nama_layanan',
            'deskripsi',    // via relasi pemesanan
            'harga',
        ];
        if (! in_array($sortBy, $allowedSort)) {
            $sortBy = 'id';
        }

        // bangun query dengan eager-load relasi
        $query = Layanan::query();

        // search di kolom lokal dan kolom relasi
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('harga', 'like', "%{$search}%");
            });
        }


        $layanans = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // jika request AJAX (pagination / search / sort)
        if ($request->ajax() && (
            $request->has('page')
            || $request->has('search')
            || $request->has('sortBy')
            || $request->has('sortOrder')
            || $request->has('perPage')
        )) {
            $html = view('layanan.partials.table', compact('layanans'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];

        return view('layanan.index', compact(
            'layanans',
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

        return view('layanan.create');
    }

    public function store(Request $request)
    {
        // validasi input
        $data = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi'    => 'required|string',
            'harga'        => 'required|integer|min:0',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'deskripsi.required'    => 'Deskripsi wajib diisi.',
            'harga.required'        => 'Harga wajib diisi.',
            'harga.integer'         => 'Harga harus berupa angka.',
            'harga.min'             => 'Harga tidak boleh negatif.',
        ]);

        // simpan
        Layanan::create($data);

        return redirect()->route('layanans.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);


        return view('layanan.edit', compact('layanan'));
    }

    public function update(Request $request, $id)
    {
        // cari layanan
        $layanan = Layanan::findOrFail($id);

        // validasi input
        $data = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi'    => 'required|string',
            'harga'        => 'required|integer|min:0',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'deskripsi.required'    => 'Deskripsi wajib diisi.',
            'harga.required'        => 'Harga wajib diisi.',
            'harga.integer'         => 'Harga harus berupa angka.',
            'harga.min'             => 'Harga tidak boleh negatif.',
        ]);

        // update
        $layanan->update($data);

        return redirect()->route('layanans.index')
            ->with('success', 'Layanan berhasil diperbarui.');
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
