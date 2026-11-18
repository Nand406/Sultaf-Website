<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MenuSeeder::class,
            StaffSeeder::class,
            // IngredientSeeder dihapus — fitur bahan baku akan dikembangkan lagi nanti
        ]);
    }
}
