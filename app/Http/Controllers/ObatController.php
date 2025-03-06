<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Kategori_obat;
use App\Models\Satuan_obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {

        // Ambil parameter sorting dari request, default sort by "nama_obat" ascending
        $sortOrder = $request->get('sortOrder', 'asc');
        $sortBy = $request->get('sortBy', 'nama_obat');



        // Daftar kolom yang diizinkan untuk sorting
        $allowedSort = ['kode_obat', 'nama_obat', 'stok', 'harga_beli', 'harga_jual', 'tanggal_kadaluarsa'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama_obat';
        }



        // Ambil parameter perPage dari request, default 10
        // Ambil nilai pencarian dari request
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search', '');



        // Mulai query dengan relasi kategori dan satuan
        // $obats = Obat::with(['kategori', 'satuan']);
        $query = Obat::with(['kategori', 'satuan']);


        // if ($search) {
        //     $obats = $obats->where(function ($query) use ($search) {
        //         $query->where('kode_obat', 'like', "%{$search}%")
        //             ->orWhere('nama_obat', 'like', "%{$search}%")
        //             ->orWhere('deskripsi', 'like', "%{$search}%")
        //             ->orWhereHas('satuan', function ($query) use ($search) {
        //                 $query->where('nama_satuan', 'like', "%{$search}%");
        //             })
        //             ->orWhereHas('kategori', function ($query) use ($search) {
        //                 $query->where('nama_kategori', 'like', "%{$search}%");
        //             });
        //     });
        // }
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_obat', 'like', "%{$search}%")
                    ->orWhere('nama_obat', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('satuan', function ($q) use ($search) {
                        $q->where('nama_satuan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kategori', function ($q) use ($search) {
                        $q->where('nama_kategori', 'like', "%{$search}%");
                    });
            });
        }


        // Terapkan sorting dan paginasi berdasarkan parameter yang dipilih
        $obats = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);


        // Jika request AJAX (misalnya pagination, search, atau sort), kembalikan partial view
        if ($request->ajax() && ($request->has('page') || $request->has('search') || $request->has('sortBy') || $request->has('sortOrder') || $request->has('perPage'))) {
            $html = view('obat.partials.table', compact('obats'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];

        // Tampilkan halaman dengan data obat
        return view('obat.index', compact('obats', 'search', 'sortBy', 'sortOrder'));
    }




    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $kategori_obats = Kategori_obat::all(); // Ambil semua kategori obat
        $satuan_obats = Satuan_obat::all(); // Ambil semua satuan obat

        return view('obat.edit', compact('obat', 'kategori_obats', 'satuan_obats'));
    }

    public function create()
    {
        $kategori_obats = Kategori_obat::all(); // Ambil semua kategori
        $satuan_obats = Satuan_obat::all(); // Ambil semua satuan

        return view('obat.create', compact('kategori_obats', 'satuan_obats'));
    }


    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'kategori_id' => 'required|exists:kategori_obats,id',
            'satuan_id' => 'required|exists:satuan_obats,id',
            'kode_obat' => 'required|string|max:255|unique:obats,kode_obat',
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tanggal_kadaluarsa' => 'required|date|after:today',
        ]);

        // Buat instance baru dari model Obat
        $obat = new Obat;
        $obat->kategori_id = $request->input('kategori_id');
        $obat->satuan_id = $request->input('satuan_id');
        $obat->kode_obat = $request->input('kode_obat');
        $obat->nama_obat = $request->input('nama_obat');
        $obat->deskripsi = $request->input('deskripsi');
        $obat->harga_beli = $request->input('harga_beli');
        $obat->harga_jual = $request->input('harga_jual');
        $obat->stok = $request->input('stok');
        $obat->tanggal_kadaluarsa = $request->input('tanggal_kadaluarsa');

        // Simpan data obat ke database
        $obat->save();

        // Redirect ke halaman daftar obat dengan pesan sukses
        return redirect()->route('obats.index')->with('success', 'Obat berhasil ditambahkan');
    }

    /**
     * Memproses update data obat.
     */

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kategori_id' => 'required|exists:kategori_obats,id',
            'satuan_id' => 'required|exists:satuan_obats,id',
            'kode_obat' => 'required|string|max:255',
            'nama_obat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'tanggal_kadaluarsa' => 'required|date',
        ]);

        // Cari obat berdasarkan ID
        $obat = Obat::findOrFail($id);

        // Update data obat
        $obat->update([
            'kategori_id' => $request->kategori_id,
            'satuan_id' => $request->satuan_id,
            'kode_obat' => $request->kode_obat,
            'nama_obat' => $request->nama_obat,
            'deskripsi' => $request->deskripsi,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
        ]);

        return redirect()->route('obats.index')->with('success', 'Obat berhasil diperbarui.');
    }


    public function destroy(Request $request)
    {
        $obatIds = $request->input('obats'); // Ambil array ID obat

        if (!empty($obatIds)) {
            Obat::whereIn('id', $obatIds)->delete();
            return redirect()->route('obats.index')->with('success', 'Obat berhasil dihapus.');
        }

        return redirect()->route('obats.index')->with('error', 'Tidak ada obat yang dipilih.');
    }
}
