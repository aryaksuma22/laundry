<?php

namespace Database\Factories;

use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayananFactory extends Factory
{
    protected $model = Layanan::class;

    public function definition()
    {
        return [
            'nama_layanan' => fake()->words(2, true), // Contoh nama generik
            'deskripsi' => fake()->sentence(),      // Contoh deskripsi generik
            'harga' => fake()->numberBetween(5000, 50000), // Contoh harga generik
        ];
    }
}
