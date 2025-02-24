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

        $suppliers = Supplier::query();

        if ($search) {
            $suppliers->where(function ($query) use ($search) {
                $query->where('nama_supplier', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Tambahkan sorting
        $suppliers = $suppliers->orderBy($sortBy, $sortOrder);

        // Ubah menjadi paginasi
        $suppliers = $suppliers->paginate($perPage);

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
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:suppliers,email',
        ], [
            'nama_supplier.unique' => 'Nama Supplier sudah ada'
        ]);

        $supplier = new Supplier;
        $supplier->nama_supplier = $request->input('nama_supplier');
        $supplier->alamat = $request->input('alamat');
        $supplier->telepon = $request->input('telepon');
        $supplier->email = $request->input('email');

        $supplier->save();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
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
            'nama_supplier' => 'required|string|max:255|unique:suppliers,nama_supplier,'.$id,
            'alamat'        => 'required|string|max:255',
            'telepon'       => 'required|string|max:15',
            'email'         => 'required|email|max:255|unique:suppliers,email,'.$id,
        ], [
            'nama_supplier.unique' => 'Nama Supplier sudah ada'
        ]);
    
        $supplier = Supplier::findOrFail($id);
        $supplier->nama_supplier = $request->input('nama_supplier');
        $supplier->alamat        = $request->input('alamat');
        $supplier->telepon       = $request->input('telepon');
        $supplier->email         = $request->input('email');
    
        $supplier->save();
    
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }
    



    // Logika Delete/Destroy Supplier

    public function destroy(Request $request)
    {

        $supplierIds = $request->input('suppliers');

        if (!empty($supplierIds)) {
            Supplier::whereIn('id', $supplierIds)->delete();
            return redirect()->route('suppliers.index')->with('success', 'Supplier Berhasil Dihapus.');
        }

        return redirect()->route('suppliers.index')->with('error', 'Tidak ada supplier yang dihapus.');
    }
}
