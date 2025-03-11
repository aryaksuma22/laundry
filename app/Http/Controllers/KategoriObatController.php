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


        $kategori_obats = Kategori_obat::query(); // Mengambil semua kategori obat dari database


        if (!empty($search)) {
            $kategori_obats = $kategori_obats->where('nama_kategori', 'like', "%$search%");
        }

        $kategori_obats = $kategori_obats->get();

        // Jika request AJAX (misalnya pagination, search, atau sort), kembalikan partial view
        if ($request->ajax() && ($request->has('page') || $request->has('search'))) {
            $html = view('kategori_obat_folder.partials.table', compact('kategori_obats'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];


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
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.string' => 'Nama kategori harus berupa teks.',
            'nama_kategori.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
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
            'nama_kategori' => 'required|string|max:255|unique:kategori_obats,nama_kategori,' . $id
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.string' => 'Nama kategori harus berupa teks.',
            'nama_kategori.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
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

    public function destroySingle($id)
    {
        $kategori_obat = Kategori_obat::where('id', $id)->first();

        if ($kategori_obat) {
            $kategori_obat->delete();
            return response()->json(['success' => true, 'message' => 'kategori_obat deleted succesfully']);
        }

        return response()->json(['success' => false, 'message' => 'Kategori Obat tidak ditemukan'], 404);
    }
}
