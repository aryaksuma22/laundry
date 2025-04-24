<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LayananController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        // Get sorting parameters (default: nama_pelanggan asc)
        $sortOrder = $request->get('sortOrder', 'asc');
        $sortBy    = $request->get('sortBy', 'nama_layanan');

        // Allowed sort columns
        $allowedSort = [
            'nama_layanan',
            'deskripsi',
            'harga',
        ];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama_layanan';
        }

        // Pagination and search parameters
        $perPage = $request->get('perPage', 10);
        $search  = $request->get('search', '');

        // Build query
        $query = Layanan::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('harga', 'like', "%{$search}%");
            });
        }

        // Apply sorting and paginate
        $layanans = $query->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        // Handle AJAX for pagination, search, sort
        if ($request->ajax() && (
            $request->has('page') || $request->has('search') ||
            $request->has('sortBy') || $request->has('sortOrder') ||
            $request->has('perPage')
        )) {
            $html = view('layanan.partials.table', compact('layanans'))->render();
            return response()->json(['html' => $html]);
        }

        // Return view
        return view('layanan.index', compact(
            'layanans',
            'search',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        return view('layanan.create');
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi'     => 'required|numeric|unique:pemesanans,no_pesanan',
            'harga'        => 'required|date',

        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'deskripsi.required'     => 'Nomor pesanan wajib diisi.',
            'harga.unique'       => 'Nomor pesanan sudah terdaftar.',

        ]);

        Layanan::create($request->all());

        return redirect()->route('layanan.index')
            ->with('success', 'layanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit($id)
    {
        $pemesanan = Layanan::findOrFail($id);
        return view('layanan.edit', compact('layanan'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'deskripsi'        => 'required|date',
            'harga'  => 'required|integer|min:0',

        ], [
            'no_pesanan.unique' => 'Nomor pesanan sudah terdaftar.',
        ]);

        $layanan = Layanan::findOrFail($id);
        $layanan->update($request->all());

        return redirect()->route('layanan.index')
            ->with('success', 'layanan berhasil diperbarui.');
    }

    /**
     * Remove multiple orders from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('layanans');

        if (!empty($ids)) {
            Layanan::whereIn('id', $ids)->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'layanan berhasil dihapus.'
                ]);
            }

            return redirect()->route('layanan.index')
                ->with('success', 'Layanan berhasil dihapus.');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada layanan yang dipilih.'
            ], 400);
        }

        return redirect()->route('layanan.index')
            ->with('error', 'Tidak ada layanan yang dipilih.');
    }

    /**
     * Remove a single order via AJAX.
     */
    public function destroySingle($id)
    {
        $layanan = Layanan::find($id);

        if ($layanan) {
            $layanan->delete();
            return response()->json([
                'success' => true,
                'message' => 'layanan berhasil dihapus.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'layanan tidak ditemukan.'
        ], 404);
    }
}
