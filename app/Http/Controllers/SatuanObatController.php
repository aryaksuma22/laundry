<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satuan_obat;

class SatuanObatController extends Controller
{
    // Controller View/Index
    public function index(Request $request)
    {
        $search = $request->get('search', '');
    
        // Mulai query dengan Satuan_obat
        $satuan_obats = Satuan_obat::query();
    
        if (!empty($search)) {
            $satuan_obats = $satuan_obats->where('nama_satuan', 'like', "%$search%");
        }
    
        // Pastikan selalu mengambil data dengan get()
        $satuan_obats = $satuan_obats->get();
    
        // Jika request AJAX (misalnya pagination, search, atau sort), kembalikan partial view
        if ($request->ajax() && ($request->has('page') || $request->has('search'))) {
            $html = view('satuan_obat_folder.partials.table', compact('satuan_obats'))->render();
            return response()->json(['html' => $html]);
        }
    
        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];
    
        return view('satuan_obat_folder.index', compact('satuan_obats', 'search'));
    }
    

    // Controller Create

    public function create()
    {
        return view('satuan_obat_folder.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255|unique:satuan_obats,nama_satuan'
        ], [
            'nama_satuan.unique' => 'Nama satuan sudah ada. Silakan gunakan nama lain.'
        ]);

        Satuan_obat::create([
            'nama_satuan' => $request->nama_satuan
        ]);

        return redirect()->route('satuan_obats.index')->with('success', 'Satuan obat berhasil ditambahkan.');
    }

    // Controller Edit

    public function edit($id)
    {
        $satuan_obat = Satuan_obat::findOrFail($id);
        return view('satuan_obat_folder.edit', compact('satuan_obat'));
    }

    // Controller Update

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255|unique:satuan_obats,nama_satuan,' . $id
        ], [
            'nama_satuan.unique' => 'Nama satuan sudah ada. Silakan gunakan nama lain.'
        ]);

        $satuan_obat = Satuan_obat::findOrFail($id);
        $satuan_obat->update([
            'nama_satuan' => $request->nama_satuan
        ]);

        return redirect()->route('satuan_obats.index')->with('success', 'Satuan obat berhasil diubah.');
    }

    // Controller Delete

    public function destroy(Request $request)
    {
        $satuan_obatIds = $request->input('satuan_obats');

        if (!empty($satuan_obatIds)) {
            Satuan_obat::whereIn('id', $satuan_obatIds)->delete();
            return redirect()->route('satuan_obats.index')->with('success', 'Obat berhasil dihapus.');
        }

        return redirect()->route('satuan_obats.index')->with('success', 'Satuan obat berhasil dihapus.');
    }

    public function destroySingle($id)
    {
        $satuan_obat = Satuan_obat::where('id', $id)->first();

        if ($satuan_obat) {
            $satuan_obat->delete();
            return response()->json(['success' => true, 'message' => 'satuan_obat deleted succesfully']);
        }

        return response()->json(['success' => false, 'message' => 'Penjualan Obat tidak ditemukan'], 404);
    }
}
