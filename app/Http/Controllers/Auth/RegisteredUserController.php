<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi inputan, termasuk kolom tambahan
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'], // Validasi untuk role
            'telepon' => ['required', 'string'], // Validasi untuk telepon
        ]);

        // Membuat user baru dengan data yang sudah divalidasi
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Menyimpan data role
            'telepon' => $request->telepon, // Menyimpan data telepon
        ]);

        // Menyebarkan event terdaftar untuk pengguna baru
        event(new Registered($user));

        // Melakukan login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke dashboard atau halaman lain setelah sukses registrasi
        return redirect(route('dashboard', absolute: false));
    }
}
