<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['nama' => 'Wagyu MB9+ Ribeye', 'stok' => 4, 'satuan' => 'kg', 'ambang_batas' => 5],
            ['nama' => 'Truffle Oil', 'stok' => 2, 'satuan' => 'botol', 'ambang_batas' => 3],
            ['nama' => 'Madura Sea Salt', 'stok' => 1.5, 'satuan' => 'kg', 'ambang_batas' => 2],
            ['nama' => 'Beras Basmati', 'stok' => 25, 'satuan' => 'kg', 'ambang_batas' => 10],
            ['nama' => 'Daging Kambing', 'stok' => 12, 'satuan' => 'kg', 'ambang_batas' => 5],
            ['nama' => 'Yogurt', 'stok' => 8, 'satuan' => 'liter', 'ambang_batas' => 4],
        ];

        foreach ($ingredients as $item) {
            Ingredient::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
