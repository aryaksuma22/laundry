<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori_obat;

class KategoriObatController extends Controller
{

    // Controller View/Index

    public function index(Request $request)
    {
        $search = $request->get('search', '');


        $kategori_obats = Kategori_obat::all(); // Mengambil semua kategori obat dari database


        if ($search) {
            $kategori_obats = Kategori_obat::where('nama_kategori', 'like', "%$search%")->get();
        }

        return view('kategori_obat_folder.index', compact('kategori_obats'));
    }



    // Controller Create

    public function create()
    {
        return view('kategori_obat_folder.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_obats,nama_kategori'
        ], [
            'nama_kategori.unique' => 'Nama kategori sudah ada. Silakan gunakan nama lain.'
        ]);

        Kategori_obat::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('kategori_obats.index')->with('success', 'Kategori obat berhasil ditambahkan.');
    }

    // Controller Edit

    public function edit($id)
    {
        $kategori_obat = Kategori_obat::findOrFail($id);
        return view('kategori_obat_folder.edit', compact('kategori_obat'));
    }

    // Controller Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_obats,nama_kategori'
        ], [
            'nama_kategori.unique' => 'Nama kategori sudah ada. Silakan gunakan nama lain.'
        ]);

        $kategori_obat = Kategori_obat::findOrFail($id);
        $kategori_obat->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->route('kategori_obats.index')->with('success', 'Kategori obat berhasil diperbaharui.');
    }

    // Controller Delete

    public function destroy(Request $request)
    {
        $kategori_obatIds = $request->input('kategori_obats'); // Ambil array ID obat

        if (!empty($kategori_obatIds)) {
            kategori_obat::whereIn('id', $kategori_obatIds)->delete();
            return redirect()->route('kategori_obats.index')->with('success', 'Obat berhasil dihapus.');
        }

        return redirect()->route('kategori_obats.index')->with('error', 'Tidak ada obat yang dipilih.');
    }
}
