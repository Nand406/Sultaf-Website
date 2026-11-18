<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $yemeni = Category::where('slug', 'yemeni-specialties')->first();
        $pakistani = Category::where('slug', 'pakistani-signature')->first();

        $menus = [
            [
                'category_id' => $yemeni?->id,
                'nama_makanan' => 'Mandi Chicken',
                'deskripsi' => 'Fragrant rice, tandoor chicken, slow-cooked in traditional clay oven.',
                'harga_makanan' => 75000,
                'rating' => 4.9,
                'spice_level' => 1,
                'is_halal' => true,
            ],
            [
                'category_id' => $pakistani?->id,
                'nama_makanan' => 'Biryani Karachi',
                'deskripsi' => 'Aromatic spices, marinated chicken, and basmati rice in Karachi style.',
                'harga_makanan' => 65000,
                'rating' => 4.8,
                'spice_level' => 3,
                'is_halal' => true,
            ],
            [
                'category_id' => $yemeni?->id,
                'nama_makanan' => 'Saltah',
                'deskripsi' => 'Rich meat stew topped with traditional fenugreek froth (Hilbeh).',
                'harga_makanan' => 85000,
                'rating' => 4.7,
                'spice_level' => 2,
                'is_halal' => true,
            ],
            [
                'category_id' => $pakistani?->id,
                'nama_makanan' => 'Seekh Kebab',
                'deskripsi' => 'Flame-grilled minced meat skewers infused with traditional spices.',
                'harga_makanan' => 55000,
                'rating' => 5.0,
                'spice_level' => 2,
                'is_halal' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['nama_makanan' => $menu['nama_makanan']],
                $menu
            );
        }
    }
}
