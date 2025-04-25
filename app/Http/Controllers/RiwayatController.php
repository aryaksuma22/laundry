<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RiwayatController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        // Get sorting parameters (default: nama_pelanggan asc)
        $sortOrder = $request->get('sortOrder', 'asc');
        $sortBy    = $request->get('sortBy', 'nama_pelanggan');


        $allowedSort = [
            'nama_pelanggan',

            'tanggal_selesai',


            'status_pesanan',
            'alamat',
            'kontak'
        ];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama_pelanggan';
        }

        // Pagination and search parameters
        $perPage = $request->get('perPage', 10);
        $search  = $request->get('search', '');

        // Build query
        $query = Riwayat::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('tanggal_selesai', 'like', "%{$search}%")
                    ->orWhere('status_pesanan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%");
            });
        }

        // Apply sorting and paginate
        $riwayats = $query->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        // Handle AJAX for pagination, search, sort
        if ($request->ajax() && (
            $request->has('page') || $request->has('search') ||
            $request->has('sortBy') || $request->has('sortOrder') ||
            $request->has('perPage')
        )) {
            $html = view('riwayat.partials.table', compact('riwayats'))->render();
            return response()->json(['html' => $html]);
        }

        // Return view
        return view('riwayat.index', compact(
            'riwayats',
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
        return view('riwayat.create');
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tanggal_selesai'        => 'required|date',
            'status_pesanan' => 'required|string|max:100',
            'alamat'         => 'required|string|max:500',
            'kontak'         => 'required|string|max:100',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'tanggal_selesai.required'        => 'Tanggal pesanan wajib diisi.',
            'status_pesanan.required' => 'Status pesanan wajib diisi.',
            'alamat.required'         => 'Alamat wajib diisi.',
            'kontak.required'         => 'Kontak wajib diisi.',
        ]);

        Riwayat::create($request->all());

        return redirect()->route('riwayat.index')
            ->with('success', 'Riwayat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit($id)
    {
        $riwayat = Riwayat::findOrFail($id);
        return view('Riwayat.edit', compact('riwayat'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tanggal_selesai'        => 'required|date',
            'status_pesanan' => 'required|string|max:100',
            'alamat'         => 'required|string|max:500',
            'kontak'         => 'required|string|max:100',
        ], [
            'no_pesanan.unique' => 'Nomor pesanan sudah terdaftar.',
        ]);

        $riwayat = riwayat::findOrFail($id);
        $riwayat->update($request->all());

        return redirect()->route('riwayat.index')
            ->with('success', 'riwayat berhasil diperbarui.');
    }

    /**
     * Remove multiple orders from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('riwayats');

        if (!empty($ids)) {
            Riwayat::whereIn('id', $ids)->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Riwayat berhasil dihapus.'
                ]);
            }

            return redirect()->route('riwayat.index')
                ->with('success', 'Riwayat berhasil dihapus.');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pesanan yang dipilih.'
            ], 400);
        }

        return redirect()->route('riwayat.index')
            ->with('error', 'Tidak ada pesanan yang dipilih.');
    }

    /**
     * Remove a single order via AJAX.
     */
    public function destroySingle($id)
    {
        $riwayat = Riwayat::find($id);

        if ($riwayat) {
            $riwayat->delete();
            return response()->json([
                'success' => true,
                'message' => 'Riwayat berhasil dihapus.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pesanan tidak ditemukan.'
        ], 404);
    }
}
