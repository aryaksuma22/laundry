<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * -------------------------------------------------------------------------
     * Fitur: Menampilkan Daftar Pengguna (Index)
     * -------------------------------------------------------------------------
     * Menampilkan daftar pengguna dengan dukungan fitur pencarian, 
     * pengurutan (sorting), dan pagination. 
     * Jika request merupakan AJAX (dari pagination, search, atau sort),
     * maka akan mengembalikan partial view untuk update konten tabel.
     */
    public function index(Request $request)
    {
        // Ambil parameter untuk sorting dengan default
        $sortOrder = $request->get('sortOrder', 'asc');
        $sortBy    = $request->get('sortBy', 'id');



        // Validasi sortOrder (hanya 'asc' atau 'desc' yang diperbolehkan)
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        // Validasi kolom yang boleh digunakan untuk sorting
        $validColumns = ['id', 'name', 'role'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'id';
        }



        // Ambil parameter untuk pagination dan pencarian
        $perPage = $request->get('perPage', 10);
        $search  = $request->get('search', '');


        // Buat query dasar
        $query = User::query();



        // Fitur Pencarian: Cari berdasarkan nama atau email
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Terapkan sorting dan pagination
        $users = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        // Jika request AJAX (misalnya pagination, search, atau sort), kembalikan partial view
        if ($request->ajax() && ($request->has('page') || $request->has('search') || $request->has('sortBy') || $request->has('sortOrder') || $request->has('perPage'))) {
            $html = view('users.partials.user-table', compact('users'))->render();
            return response()->json(['html' => $html]);
        }

        $headers = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
        ];


        // Untuk navigasi full view (misalnya dari sidebar), kembalikan view lengkap
        return view('users.account-management', compact('users'));
    }

    /**
     * -------------------------------------------------------------------------
     * Fitur: Menampilkan Form Pembuatan Pengguna Baru
     * -------------------------------------------------------------------------
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * -------------------------------------------------------------------------
     * Fitur: Menyimpan Pengguna Baru ke Database
     * -------------------------------------------------------------------------
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'name.string' => 'Nama pengguna harus berupa teks.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Membuat instance User baru dan mengisi data
        $user = new User;
        $user->name     = $request->input('name');
        $user->email    = $request->input('email');
        $user->role     = $request->input('role');
        $user->telepon  = $request->input('telepon');
        // Hash password sebelum disimpan ke database
        $user->password = Hash::make($request->input('password'));

        // Simpan data ke database
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('account.management')->with('success', 'Akun berhasil ditambahkan');
    }

    /**
     * -------------------------------------------------------------------------
     * Fitur: Menampilkan Detail Pengguna (Optional)
     * -------------------------------------------------------------------------
     */
    public function show(string $id)
    {
        // Belum diimplementasikan, bisa digunakan untuk menampilkan detail user
    }

    /**
     * -------------------------------------------------------------------------
     * Fitur: Menampilkan Form Edit Pengguna
     * -------------------------------------------------------------------------
     */
    public function edit($id)
    {
        // Ambil data pengguna berdasarkan ID
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * -------------------------------------------------------------------------
     * Fitur: Mengupdate Data Pengguna di Database
     * -------------------------------------------------------------------------
     */
    public function update(Request $request, $id)
    {
        // Validasi input yang diterima dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'name.string' => 'Nama pengguna harus berupa teks.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
        ]);

        // Cari user berdasarkan ID dan update datanya
        $user = User::findOrFail($id);
        $user->name    = $request->input('name');
        $user->email   = $request->input('email');
        $user->role    = $request->input('role');
        $user->telepon = $request->input('telepon');
        $user->save();

        // Redirect ke daftar pengguna dengan pesan sukses
        return redirect()->route('account.management', [
            'search' => request('search'),
            'sortBy' => request('sortBy'),
            'sortOrder' => request('sortOrder'),
            'perPage' => request('perPage')
        ])->with('success', 'User updated successfully');
    }

    /**
     * -------------------------------------------------------------------------
     * Fitur: Menghapus Pengguna (Mass Delete)
     * -------------------------------------------------------------------------
     */
    public function destroy(Request $request)
    {
        // Ambil array ID pengguna yang dipilih
        $userIds = $request->input('users');

        // Jika ada ID yang dipilih, hapus semua pengguna tersebut
        if ($userIds && is_array($userIds)) {
            User::whereIn('id', $userIds)->delete();
        }

        // Redirect kembali ke halaman account management dengan pesan sukses
        return redirect()->route('account.management')->with('success', 'Users deleted successfully.');
    }


    public function destroySingle($id)
    {
        // Cari pengguna berdasarkan ID
        $user = User::find($id);

        // Jika user ditemukan, hapus
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        }

        // Jika tidak ditemukan, kirim respon error
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
}
