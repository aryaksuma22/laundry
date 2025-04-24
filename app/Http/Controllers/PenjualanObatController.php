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

        $query = Penjualan_obat::with(['obat']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->Where('jumlah', 'like', "%{$search}%")
                    ->orWhere('harga_jual', 'like', "%{$search}%")
                    ->orWhere('total_harga', 'like', "%{$search}%")
                    ->orWhereHas('obat', function ($q) use ($search) {
                        $q->where('nama_obat', 'like', "%{$search}%");
                    });
            });
        }


        if ($sortBy == 'nama_obat') {
            $query = $query->join('obats', 'penjualan_obats.obat_id', '=', 'obats.id')
                ->orderBy('obats.nama_obat', $sortOrder)
                ->select('penjualan_obats.*');
        } else {
            $query = $query->orderBy($sortBy, $sortOrder);
        }

        $penjualan_obats = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // Jika request AJAX (misalnya pagination, search, atau sort), kembalikan partial view
        if ($request->ajax() && ($request->has('page') || $request->has('search') || $request->has('sortBy') || $request->has('sortOrder') || $request->has('perPage'))) {
            $html = view('penjualan_obat_folder.partials.table', compact('penjualan_obats'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];


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
            'obat_id'    => 'required|exists:obats,id',
            'jumlah'     => 'required|integer|min:1',
            'harga_jual' => 'required|numeric|min:0',
        ], [
            'obat_id.required'    => 'Obat wajib dipilih.',
            'obat_id.exists'      => 'Obat yang dipilih tidak valid.',
            'jumlah.required'     => 'Jumlah penjualan wajib diisi.',
            'jumlah.integer'      => 'Jumlah harus berupa angka bulat.',
            'jumlah.min'          => 'Jumlah penjualan minimal 1.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric'  => 'Harga jual harus berupa angka.',
            'harga_jual.min'      => 'Harga jual tidak boleh kurang dari 0.',
        ]);

        // Simpan data penjualan obat
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
        ], [
            'obat_id.required'    => 'Obat wajib dipilih.',
            'obat_id.exists'      => 'Obat yang dipilih tidak valid.',
            'jumlah.required'     => 'Jumlah penjualan wajib diisi.',
            'jumlah.integer'      => 'Jumlah harus berupa angka bulat.',
            'jumlah.min'          => 'Jumlah penjualan minimal 1.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric'  => 'Harga jual harus berupa angka.',
            'harga_jual.min'      => 'Harga jual tidak boleh kurang dari 0.',
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
            Penjualan_obat::whereIn('id', $penjualan_obatIds)->delete();
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus.']);
            }
            return redirect()->route('penjualan_obats.index')->with('success', 'Transaksi berhasil dihapus.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada transaksi yang dipilih.'], 400);
        }

        return redirect()->route('penjualan_obats.index')->with('error', 'Tidak ada transaksi yang dipilih.');
    }

    public function destroySingle($id)
    {
        $penjualan_obat = Penjualan_obat::where('id', $id)->first();

        if ($penjualan_obat) {
            $penjualan_obat->delete();
            return response()->json(['success' => true, 'message' => 'penjualan_obat deleted succesfully']);
        }

        return response()->json(['success' => false, 'message' => 'Penjualan Obat tidak ditemukan'], 404);
    }
}
