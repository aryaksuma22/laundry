<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // Tampilan Index Supplier

    public function index(Request $request)
    {

        $search = $request->get('search', '');

        $sortBy = $request->get('sortBy', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');

        $perPage = $request->get('perPage', 10);

        $allowedSort = ['id', 'nama_supplier'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama_supplier';
        }

        $query = Supplier::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_supplier', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Tambahkan sorting
        $suppliers = $query->orderBy($sortBy, $sortOrder);

        // Ubah menjadi paginasi
        $suppliers = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // Jika request AJAX (misalnya pagination, search, atau sort), kembalikan partial view
        if ($request->ajax() && ($request->has('page') || $request->has('search') || $request->has('sortBy') || $request->has('sortOrder') || $request->has('perPage'))) {
            $html = view('supplier_folder.partials.table', compact('suppliers'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];

        return view('supplier_folder.index', compact('suppliers', 'search', 'sortBy', 'sortOrder'));
    }


    // Tampilan Create Supplier

    public function create()
    {
        return view('supplier_folder.create');
    }

    // Logika Create/Store Supplier

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:suppliers,nama_supplier',
            'alamat'        => 'required|string|max:255',
            'telepon'       => 'required|string|max:15',
            'email'         => 'required|string|email|max:255|unique:suppliers,email',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.string'   => 'Nama supplier harus berupa teks.',
            'nama_supplier.max'      => 'Nama supplier tidak boleh lebih dari 255 karakter.',
            'nama_supplier.unique'   => 'Nama supplier sudah ada.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'alamat.string'          => 'Alamat harus berupa teks.',
            'alamat.max'             => 'Alamat tidak boleh lebih dari 255 karakter.',
            'telepon.required'       => 'Telepon wajib diisi.',
            'telepon.string'         => 'Telepon harus berupa teks.',
            'telepon.max'            => 'Telepon tidak boleh lebih dari 15 angka.',
            'email.required'         => 'Email wajib diisi.',
            'email.string'           => 'Email harus berupa teks.',
            'email.email'            => 'Email harus berupa alamat email yang valid.',
            'email.max'              => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique'           => 'Email sudah terdaftar.',
        ]);

        $supplier = new Supplier;
        $supplier->nama_supplier = $request->input('nama_supplier');
        $supplier->alamat = $request->input('alamat');
        $supplier->telepon = $request->input('telepon');
        $supplier->email = $request->input('email');

        $supplier->save();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    // Tampilan Edit Supplier

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('supplier_folder.edit', compact('supplier'));
    }

    // Logika Edit/Update Supplier
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:suppliers,nama_supplier,' . $id,
            'alamat'        => 'required|string|max:255',
            'telepon'       => 'required|string|max:15',
            'email'         => 'required|email|max:255|unique:suppliers,email,' . $id,
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.string'   => 'Nama supplier harus berupa teks.',
            'nama_supplier.max'      => 'Nama supplier tidak boleh lebih dari 255 karakter.',
            'nama_supplier.unique'   => 'Nama supplier sudah ada.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'alamat.string'          => 'Alamat harus berupa teks.',
            'alamat.max'             => 'Alamat tidak boleh lebih dari 255 karakter.',
            'telepon.required'       => 'Telepon wajib diisi.',
            'telepon.string'         => 'Telepon harus berupa teks.',
            'telepon.max'            => 'Telepon tidak boleh lebih dari 15 angka.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Email harus berupa alamat email yang valid.',
            'email.max'              => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique'           => 'Email sudah terdaftar.',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->nama_supplier = $request->input('nama_supplier');
        $supplier->alamat        = $request->input('alamat');
        $supplier->telepon       = $request->input('telepon');
        $supplier->email         = $request->input('email');

        $supplier->save();

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diubah.');
    }




    // Logika Delete/Destroy Supplier

    public function destroy(Request $request)
    {
        $supplierIds = $request->input('suppliers');

        if (!empty($supplierIds)) {
            Supplier::whereIn('id', $supplierIds)->delete();
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Supplier berhasil dihapus.']);
            }
            return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada Supplier yang dipilih.'], 400);
        }

        return redirect()->route('suppliers.index')->with('error', 'Tidak ada Supplier yang dipilih.');
    }

    public function destroySingle($id)
    {
        $supplier = Supplier::where('id', $id)->first();

        if ($supplier) {
            $supplier->delete();
            return response()->json(['success' => true, 'message' => 'Supplier berhasil dihapus']);
        }

        return response()->json(['success' => false, 'message' => 'Supplier tidak ditemukan'], 404);
    }
}
