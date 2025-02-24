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

        $satuan_obats = Satuan_obat::all(); // Mengambil semua satuan obat dari database

        if ($search) {
            $satuan_obats = Satuan_obat::where('nama_satuan', 'like', "%$search%")->get();
        }

        return view('satuan_obat_folder.index', compact('satuan_obats'));
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
            satuan_obat::whereIn('id', $satuan_obatIds)->delete();
            return redirect()->route('satuan_obats.index')->with('success', 'Obat berhasil dihapus.');
        }

        return redirect()->route('satuan_obats.index')->with('success', 'Satuan obat berhasil dihapus.');
    }
}
