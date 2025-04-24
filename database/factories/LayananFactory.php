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
            'nama_layanan' => $this->faker->word,  // Generates a random service name
            'deskripsi' => $this->faker->paragraph, // Generates a random description
            'harga' => $this->faker->numberBetween(10000, 500000), // Generates a random price between 10k to 500k
        ];
    }
}
