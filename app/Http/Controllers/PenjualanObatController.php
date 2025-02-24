<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan_obat;
use App\Models\Obat;


class PenjualanObatController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->get('search', '');

        $sortBy = $request->get('sortBy', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        $perPage = $request->get('perPage', 10);

        $allowedSort = ['id', 'nama_obat', 'jumlah', 'harga_jual', 'total_harga'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'id';
        }

        $penjualan_obats = Penjualan_obat::with(['obat']);

        if ($search) {
            $penjualan_obats = $penjualan_obats->where(function ($query) use ($search) {
                $query->Where('jumlah', 'like', "%{$search}%")
                    ->orWhere('harga_jual', 'like', "%{$search}%")
                    ->orWhere('total_harga', 'like', "%{$search}%")
                    ->orWhereHas('obat', function ($query) use ($search) {
                        $query->where('nama_obat', 'like', "%{$search}%");
                    });
            });
        }

        if ($sortBy == 'nama_obat') {
            $penjualan_obats = $penjualan_obats->join('obats', 'penjualan_obats.obat_id', '=', 'obats.id')
                ->orderBy('obats.nama_obat', $sortOrder)
                ->select('penjualan_obats.*');
        } else {
            $penjualan_obats = $penjualan_obats->orderBy($sortBy, $sortOrder);
        }

        $penjualan_obats = $penjualan_obats->orderBy($sortBy, $sortOrder)->paginate($perPage);


        return view('penjualan_obat_folder.index', compact('penjualan_obats', 'search', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        // Ambil data obat untuk mengisi dropdown
        $obats = Obat::all();

        return view('penjualan_obat_folder.create', compact('obats'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'obat_id'           => 'required|exists:obats,id',
            'jumlah'            => 'required|integer|min:1',
            'harga_jual'        => 'required|numeric|min:0',
        ]);

        // Simpan data pembelian obat
        $penjualan_obat = new penjualan_obat();
        $penjualan_obat->obat_id = $request->obat_id;
        $penjualan_obat->jumlah = $request->jumlah;
        $penjualan_obat->harga_jual = $request->harga_jual;
        // Hitung total_harga sebagai jumlah x harga_beli
        $penjualan_obat->total_harga = $request->jumlah * $request->harga_jual;
        $penjualan_obat->save();

        return redirect()->route('penjualan_obats.index')
            ->with('success', 'Data Penjualan obat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Cari data penjualan obat berdasarkan ID
        $penjualan_obat = Penjualan_obat::findOrFail($id);

        // Ambil data obat untuk dropdown
        $obats = Obat::all();

        return view('penjualan_obat_folder.edit', compact('penjualan_obat', 'obats'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'obat_id'    => 'required|exists:obats,id',
            'jumlah'     => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        // Ambil data penjualan obat yang akan diupdate
        $penjualan_obat = Penjualan_obat::findOrFail($id);

        // Update data
        $penjualan_obat->obat_id     = $request->obat_id;
        $penjualan_obat->jumlah      = $request->jumlah;
        $penjualan_obat->harga_jual  = $request->harga_jual;
        $penjualan_obat->total_harga = $request->jumlah * $request->harga_jual;
        $penjualan_obat->save();

        return redirect()->route('penjualan_obats.index')
            ->with('success', 'Data penjualan obat berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $penjualan_obatIds = $request->input('penjualan_obats'); // array ID penjualan_obats

        if (!empty($penjualan_obatIds)) {
            // Hapus di tabel penjualan_obats
            Penjualan_obat::whereIn('id', $penjualan_obatIds)->delete();

            return redirect()->route('penjualan_obats.index')
                ->with('success', 'Data Penjualan berhasil dihapus.');
        }

        return redirect()->route('penjualan_obats.index')
            ->with('error', 'Tidak ada data penjualan yang dipilih.');
    }
}
