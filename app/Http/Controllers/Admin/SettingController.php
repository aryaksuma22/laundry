<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Meskipun model sudah handle, import tidak masalah

class SettingController extends Controller
{
    /**
     * Display a listing of the settings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua settings, urutkan berdasarkan grup lalu label, kemudian kelompokkan berdasarkan grup
        $settings = Setting::orderBy('group')->orderBy('label')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the specified settings in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [];
        $settingsToValidate = Setting::all();

        // Bangun aturan validasi secara dinamis berdasarkan tipe setting
        foreach ($settingsToValidate as $setting) {
            $validationRule = 'nullable|string|max:191'; // Max length untuk string
            if ($setting->type === 'number') {
                $validationRule = 'nullable|numeric';
            } elseif ($setting->type === 'boolean' || $setting->type === 'checkbox') {
                // Untuk checkbox, kita tidak validasi value secara ketat,
                // karena jika tidak dicentang, key-nya tidak akan dikirim.
                // Kita akan handle value-nya di bawah.
                $validationRule = 'nullable'; // Tidak ada value spesifik untuk divalidasi
            } elseif ($setting->type === 'textarea') {
                $validationRule = 'nullable|string';
            }
            // Anda bisa tambahkan validasi lain seperti 'email', 'url', dll jika ada tipe tersebut
            $rules[$setting->key] = $validationRule;
        }

        $validatedData = $request->validate($rules);

        foreach ($settingsToValidate as $setting) { // Loop berdasarkan setting yang ada di DB
            $key = $setting->key;
            $value = null;

            if ($setting->type === 'boolean' || $setting->type === 'checkbox') {
                // Jika checkbox, value adalah '1' jika dicentang (request->has()), '0' jika tidak.
                $value = $request->has($key) ? '1' : '0';
            } elseif (isset($validatedData[$key])) {
                // Ambil dari data yang sudah divalidasi jika ada
                $value = $validatedData[$key];
            }

            // Hanya update jika value dari request tidak null (atau ini adalah boolean)
            // Ini untuk mencegah field kosong menimpa value yang sudah ada jika field tersebut tidak wajib
            // Namun, karena kita menggunakan nullable, value bisa jadi null.
            // Jadi, kita pastikan value yang dikirim ada di $validatedData atau ini adalah boolean
            if ($setting->type === 'boolean' || $setting->type === 'checkbox' || array_key_exists($key, $validatedData)) {
                $settingToUpdate = Setting::find($key);
                if ($settingToUpdate) {
                    $settingToUpdate->value = $value;
                    $settingToUpdate->save(); // Ini akan mentrigger event 'saved' di model untuk clear cache
                }
            }
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
