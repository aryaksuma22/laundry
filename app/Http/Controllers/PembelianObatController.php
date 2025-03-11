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

        $sortOrder = $request->get('sortOrder', 'asc');
        $sortBy = $request->get('sortBy', 'id');

        $perPage = $request->get('perPage', 10);
        $search = $request->get('search', '');

        $allowedSort = ['id', 'nama_obat', 'nama_supplier', 'jumlah', 'harga_beli', 'total_harga', 'tanggal_pembelian'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'id';
        }

        $query = Pembelian_obat::with(['obat', 'supplier']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->Where('jumlah', 'like', "%{$search}%")
                    ->orWhere('harga_beli', 'like', "%{$search}%")
                    ->orWhere('total_harga', 'like', "%{$search}%")
                    ->orWhere('tanggal_pembelian', 'like', "%{$search}%")
                    ->orWhereHas('obat', function ($q) use ($search) {
                        $q->where('nama_obat', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('nama_supplier', 'like', "%{$search}%");
                    });
            });
        }

        if ($sortBy == 'nama_obat') {
            $query = $query->join('obats', 'pembelian_obats.obat_id', '=', 'obats.id')
                ->orderBy('obats.nama_obat', $sortOrder)
                ->select('pembelian_obats.*');
        } elseif ($sortBy == 'nama_supplier') {
            $query = $query->join('suppliers', 'pembelian_obats.supplier_id', '=', 'suppliers.id')
                ->orderBy('suppliers.nama_supplier', $sortOrder)
                ->select('pembelian_obats.*');
        } else {
            $query = $query->orderBy($sortBy, $sortOrder);
        }

        $pembelian_obats = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // Jika request AJAX (misalnya pagination, search, atau sort), kembalikan partial view
        if ($request->ajax() && ($request->has('page') || $request->has('search') || $request->has('sortBy') || $request->has('sortOrder') || $request->has('perPage'))) {
            $html = view('pembelian_obat_folder.partials.table', compact('pembelian_obats'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];

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
        ], [
            'obat_id.required'           => 'Obat wajib dipilih.',
            'obat_id.exists'             => 'Obat yang dipilih tidak valid.',
            'supplier_id.required'       => 'Supplier wajib dipilih.',
            'supplier_id.exists'         => 'Supplier yang dipilih tidak valid.',
            'jumlah.required'            => 'Jumlah pembelian wajib diisi.',
            'jumlah.integer'             => 'Jumlah harus berupa angka bulat.',
            'jumlah.min'                 => 'Jumlah pembelian minimal 1.',
            'harga_beli.required'        => 'Harga beli wajib diisi.',
            'harga_beli.numeric'         => 'Harga beli harus berupa angka.',
            'harga_beli.min'             => 'Harga beli tidak boleh kurang dari 0.',
            'tanggal_pembelian.required' => 'Tanggal pembelian wajib diisi.',
            'tanggal_pembelian.date'     => 'Tanggal pembelian harus berupa tanggal yang valid.',
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
        ], [
            'obat_id.required'           => 'Obat wajib dipilih.',
            'obat_id.exists'             => 'Obat yang dipilih tidak valid.',
            'supplier_id.required'       => 'Supplier wajib dipilih.',
            'supplier_id.exists'         => 'Supplier yang dipilih tidak valid.',
            'jumlah.required'            => 'Jumlah pembelian wajib diisi.',
            'jumlah.integer'             => 'Jumlah harus berupa angka bulat.',
            'jumlah.min'                 => 'Jumlah pembelian minimal 1.',
            'harga_beli.required'        => 'Harga beli wajib diisi.',
            'harga_beli.numeric'         => 'Harga beli harus berupa angka.',
            'harga_beli.min'             => 'Harga beli tidak boleh kurang dari 0.',
            'tanggal_pembelian.required' => 'Tanggal pembelian wajib diisi.',
            'tanggal_pembelian.date'     => 'Tanggal pembelian harus berupa tanggal yang valid.',
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

    public function destroySingle($id)
    {
        $pembelian_obat = Pembelian_obat::where('id', $id)->first();

        if ($pembelian_obat) {
            $pembelian_obat->delete();
            return response()->json(['success' => true, 'message' => 'pembelian_obat deleted succesfully']);
        }

        return response()->json(['success' => false, 'message' => 'Pembelian Obat tidak ditemukan'], 404);
    }
}
