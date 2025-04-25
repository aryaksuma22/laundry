<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PemesananController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        // Get sorting parameters (default: nama_pelanggan asc)
        $sortOrder = $request->get('sortOrder', 'asc');
        $sortBy    = $request->get('sortBy', 'nama_pelanggan');
        // Pagination and search parameters
        $perPage = $request->get('perPage', 10);
        $search  = $request->get('search', '');
        // Allowed sort columns
        $allowedSort = [
            'nama_pelanggan',
            'no_pesanan',
            'tanggal',
            'berat_pesanan',
            'total_harga',
            'status_pesanan',
            'alamat',
            'kontak'
        ];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama_pelanggan';
        }

        $layanans = Layanan::all();

        // Build query
        $query = Pemesanan::with(['layanan']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_pesanan', 'like', "%{$search}%")
                    ->orWhere('tanggal', 'like', "%{$search}%")
                    ->orWhereHas('layanan', function ($q) use ($search) {
                        $q->where('nama_layanan', 'like', "%{$search}%");
                    })
                    ->orWhere('berat_pesanan', 'like', "%{$search}%")
                    ->orWhere('total_harga', 'like', "%{$search}%")
                    ->orWhere('status_pesanan', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%");
            });
        }

        // Apply sorting and paginate
        $pemesanans = $query->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        // Handle AJAX for pagination, search, sort
        if ($request->ajax() && (
            $request->has('page')
            || $request->has('search')
            || $request->has('sortBy')
            || $request->has('sortOrder')
            || $request->has('perPage')
        )) {
            $html = view('pemesanan.partials.table', compact('pemesanans'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];

        // Return view
        return view('pemesanan.index', compact(
            'pemesanans',
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
        $layanans   = Layanan::all();
        return view('pemesanan.create', compact('layanans'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_pesanan'     => 'required|numeric|unique:pemesanans,no_pesanan',
            'tanggal'        => 'required|date',
            'layanan_id'     => 'required|exists:layanans,id',
            'berat_pesanan'  => 'required|integer|min:0',
            'total_harga'    => 'required|integer|min:0',
            'status_pesanan' => 'required|string|max:100',
            'alamat'         => 'required|string|max:500',
            'kontak'         => 'required|string|max:100',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'no_pesanan.required'     => 'Nomor pesanan wajib diisi.',
            'no_pesanan.unique'       => 'Nomor pesanan sudah terdaftar.',
            'tanggal.required'        => 'Tanggal pesanan wajib diisi.',
            'layanan_id.required'    => 'Layanan wajib dipilih.',
            'layanan_id.exists'      => 'Layanan tidak valid.',
            'berat_pesanan.required'  => 'Berat pesanan wajib diisi.',
            'total_harga.required'    => 'Total harga wajib diisi.',
            'status_pesanan.required' => 'Status pesanan wajib diisi.',
            'alamat.required'         => 'Alamat wajib diisi.',
            'kontak.required'         => 'Kontak wajib diisi.',
        ]);

        Pemesanan::create($request->all());

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $layanans  = Layanan::all();
        return view('pemesanan.edit', compact('pemesanan', 'layanans'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_pesanan'     => [
                'required',
                'numeric',
                Rule::unique('pemesanans', 'no_pesanan')->ignore($id),
            ],
            'layanan_id'     => 'required|exists:layanans,id',
            'tanggal'        => 'required|date',
            'berat_pesanan'  => 'required|integer|min:1',
            'status_pesanan' => 'required|string|max:100',
            'alamat'         => 'required|string|max:500',
            'kontak'         => 'required|string|max:100',
        ], [
            'no_pesanan.unique'   => 'Nomor pesanan sudah terdaftar.',
            'layanan_id.required' => 'Layanan wajib dipilih.',
            'layanan_id.exists'   => 'Layanan tidak valid.',
        ]);

        // Recalculate total harga based on selected layanan
        $layanan             = Layanan::findOrFail($data['layanan_id']);
        $data['total_harga'] = $layanan->harga * $data['berat_pesanan'];

        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->update($data);

        return redirect()->route('pemesanan.index')
                         ->with('success', 'Pemesanan berhasil diperbarui.');
    }


    /**
     * Remove multiple orders from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('pemesanans');

        if (!empty($ids)) {
            Pemesanan::whereIn('id', $ids)->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pemesanan berhasil dihapus.'
                ]);
            }

            return redirect()->route('pemesanan.index')
                ->with('success', 'Pemesanan berhasil dihapus.');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pesanan yang dipilih.'
            ], 400);
        }

        return redirect()->route('pemesanan.index')
            ->with('error', 'Tidak ada pesanan yang dipilih.');
    }

    /**
     * Remove a single order via AJAX.
     */
    public function destroySingle($id)
    {
        $pemesanan = Pemesanan::find($id);

        if ($pemesanan) {
            $pemesanan->delete();
            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dihapus.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pesanan tidak ditemukan.'
        ], 404);
    }
}
