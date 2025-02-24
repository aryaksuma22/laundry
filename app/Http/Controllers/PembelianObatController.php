<?php

namespace App\Http\Controllers;

use App\Models\Pembelian_obat;
use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;


class PembelianObatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $sortBy = $request->get('sortBy', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        $perPage = $request->get('perPage', 10);

        $allowedSort = ['id', 'nama_obat', 'nama_supplier', 'jumlah', 'harga_beli', 'total_harga', 'tanggal_pembelian'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'id';
        }

        $pembelian_obats = Pembelian_obat::with(['obat', 'supplier']);

        if ($search) {
            $pembelian_obats = $pembelian_obats->where(function ($query) use ($search) {
                $query->Where('jumlah', 'like', "%{$search}%")
                    ->orWhere('harga_beli', 'like', "%{$search}%")
                    ->orWhere('total_harga', 'like', "%{$search}%")
                    ->orWhere('tanggal_pembelian', 'like', "%{$search}%")
                    ->orWhereHas('obat', function ($query) use ($search) {
                        $query->where('nama_obat', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($query) use ($search) {
                        $query->where('nama_supplier', 'like', "%{$search}%");
                    });
            });
        }

        if ($sortBy == 'nama_obat') {
            $pembelian_obats = $pembelian_obats->join('obats', 'pembelian_obats.obat_id', '=', 'obats.id')
                ->orderBy('obats.nama_obat', $sortOrder)
                ->select('pembelian_obats.*');
        } elseif ($sortBy == 'nama_supplier') {
            $pembelian_obats = $pembelian_obats->join('suppliers', 'pembelian_obats.supplier_id', '=', 'suppliers.id')
                ->orderBy('suppliers.nama_supplier', $sortOrder)
                ->select('pembelian_obats.*');
        } else {
            $pembelian_obats = $pembelian_obats->orderBy($sortBy, $sortOrder);
        }

        $pembelian_obats = $pembelian_obats->orderBy($sortBy, $sortOrder)->paginate($perPage);


        return view('pembelian_obat_folder.index', compact('pembelian_obats', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        // Ambil data obat dan supplier untuk mengisi dropdown
        $obats = Obat::all();
        $suppliers = Supplier::all();

        return view('pembelian_obat_folder.create', compact('obats', 'suppliers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'obat_id'           => 'required|exists:obats,id',
            'supplier_id'       => 'required|exists:suppliers,id',
            'jumlah'            => 'required|integer|min:1',
            'harga_beli'        => 'required|numeric|min:0',
            'tanggal_pembelian' => 'required|date',
        ]);

        // Simpan data pembelian obat
        $pembelian_obat = new Pembelian_obat();
        $pembelian_obat->obat_id = $request->obat_id;
        $pembelian_obat->supplier_id = $request->supplier_id;
        $pembelian_obat->jumlah = $request->jumlah;
        $pembelian_obat->harga_beli = $request->harga_beli;
        // Hitung total_harga sebagai jumlah x harga_beli
        $pembelian_obat->total_harga = $request->jumlah * $request->harga_beli;
        $pembelian_obat->tanggal_pembelian = $request->tanggal_pembelian;
        $pembelian_obat->save();

        return redirect()->route('pembelian_obats.index')
            ->with('success', 'Data pembelian obat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Cari data pembelian obat berdasarkan ID
        $pembelian_obat = Pembelian_obat::findOrFail($id);

        // Ambil data obat dan supplier untuk dropdown
        $obats = Obat::all();
        $suppliers = Supplier::all();

        return view('pembelian_obat_folder.edit', compact('pembelian_obat', 'obats', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'obat_id'           => 'required|exists:obats,id',
            'supplier_id'       => 'required|exists:suppliers,id',
            'jumlah'            => 'required|integer|min:1',
            'harga_beli'        => 'required|numeric|min:0',
            'tanggal_pembelian' => 'required|date',
        ]);

        // Ambil data pembelian obat yang akan diupdate
        $pembelian_obat = Pembelian_obat::findOrFail($id);

        $pembelian_obat->obat_id = $request->obat_id;
        $pembelian_obat->supplier_id = $request->supplier_id;
        $pembelian_obat->jumlah = $request->jumlah;
        $pembelian_obat->harga_beli = $request->harga_beli;
        // Total harga dihitung sebagai jumlah x harga_beli
        $pembelian_obat->total_harga = $request->jumlah * $request->harga_beli;
        $pembelian_obat->tanggal_pembelian = $request->tanggal_pembelian;

        $pembelian_obat->save();

        return redirect()->route('pembelian_obats.index')
            ->with('success', 'Data pembelian obat berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $pembelian_obatIds = $request->input('pembelian_obats'); // Ambil array ID obat

        if (!empty($pembelian_obatIds)) {
            Obat::whereIn('id', $pembelian_obatIds)->delete();
            return redirect()->route('pembelian_obats.index')->with('success', 'Data Pembelian berhasil dihapus.');
        }

        return redirect()->route('pembelian_obats.index')->with('error', 'Tidak ada obat yang dipilih.');
    }
}
