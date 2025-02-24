<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil query parameter untuk sorting
        $sortBy = $request->get('sortBy', 'id'); // Default sort by ID
        $sortOrder = $request->get('sortOrder', 'asc'); // Default ascending order
    
        // Validasi parameter sortOrder untuk memastikan hanya 'asc' atau 'desc' yang diterima
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc'; // Set ke ascending jika tidak valid
        }
    
        // Validasi bahwa sortBy adalah salah satu kolom yang valid (id, name, role)
        $validColumns = ['id', 'name', 'role'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id'; // Set ke default sort by ID jika kolom tidak valid
        }
    
        // Ambil nilai perPage dari request, dengan default 10
        $perPage = $request->get('perPage', 10); 
    
        // Ambil nilai search dari request untuk pencarian
        $search = $request->get('search', '');
    
        // Cek apakah pencarian kosong, jika iya ambil semua data tanpa filter
        if ($search) {
            // Ambil data pengguna yang terfilter berdasarkan pencarian
            $users = User::where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orderBy($sortBy, $sortOrder)
                        ->paginate($perPage); 
        } else {
            // Ambil data pengguna tanpa filter pencarian
            $users = User::orderBy($sortBy, $sortOrder)
                        ->paginate($perPage);
        }
    
        // Kirim data ke view
        return view('users.account-management', compact('users', 'search'));
    }
    
    
    
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed', // Password minimal 8 karakter dan konfirmasi password
            'role' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
        ]);

        // Membuat user baru dengan data yang diterima
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->telepon = $request->input('telepon');

        // Hash password sebelum disimpan ke database
        $user->password = Hash::make($request->input('password'));

        // Simpan ke database
        $user->save();

        // Redirect ke halaman daftar pengguna atau halaman lain
        return redirect()->route('account.management')->with('success', 'Akun berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil data pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Kembalikan view dengan data pengguna yang akan diedit
        return view('users.edit', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
        ]);

        // Cari pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Update data pengguna
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->telepon = $request->input('telepon');

        // Simpan perubahan
        $user->save();

        // Redirect ke halaman daftar pengguna atau halaman lain yang diinginkan
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Ambil ID pengguna yang dipilih
        $userIds = $request->input('users');

        // Pastikan ada ID yang dipilih
        if ($userIds && is_array($userIds)) {
            // Hapus semua pengguna yang dipilih
            User::whereIn('id', $userIds)->delete();
        }

        return redirect()->route('account.management')->with('success', 'Users deleted successfully.');
    }
}
