<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'key'; // Tentukan primary key
    public $incrementing = false; // Karena primary key bukan integer auto-increment
    protected $keyType = 'string'; // Tipe data primary key

    protected $fillable = [
        'key',
        'value',
        'label',
        'group',
        'type',
        'description',
    ];

    // Helper untuk mendapatkan nilai setting dengan caching
    public static function getValue(string $key, $default = null)
    {
        $cacheKey = 'setting_' . $key;

        // Coba ambil dari cache dulu
        if (Cache::has($cacheKey)) {
            $settingValue = Cache::get($cacheKey);
        } else {
            $setting = self::find($key);
            $settingValue = $setting ? $setting->value : $default;

            // Simpan ke cache
            Cache::forever($cacheKey, $settingValue);
        }

        // Jika tipe-nya json, coba decode
        // Ini bisa diperluas jika kita punya kolom 'type' di DB untuk setting
        // dan ingin melakukan casting otomatis
        $decoded = json_decode($settingValue, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $settingValue;
    }

    // Helper untuk menyimpan nilai setting dan membersihkan cache
    public static function setValue(string $key, $value, string $label = null, string $group = 'Umum', string $type = 'text', string $description = null)
    {
        if (is_array($value) || is_object($value)) {
            $valueToStore = json_encode($value);
        } else {
            $valueToStore = $value;
        }

        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $valueToStore,
                'label' => $label ?: ucfirst(str_replace('_', ' ', $key)), // Generate label jika null
                'group' => $group,
                'type' => $type,
                'description' => $description,
            ]
        );

        // Bersihkan cache untuk key ini
        Cache::forget('setting_' . $key);
        return $setting;
    }

    // Override event 'saved' dan 'deleted' untuk membersihkan cache
    protected static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget('setting_' . $setting->key);
            Cache::forever('setting_' . $setting->key, $setting->value); // Update cache juga
        });

        static::deleted(function ($setting) {
            Cache::forget('setting_' . $setting->key);
        });
    }
}
