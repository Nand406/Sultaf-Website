<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Yemeni Specialties', 'slug' => 'yemeni-specialties', 'order' => 1],
            ['name' => 'Pakistani Signature', 'slug' => 'pakistani-signature', 'order' => 2],
            ['name' => 'Appetizers', 'slug' => 'appetizers', 'order' => 3],
            ['name' => 'Desserts', 'slug' => 'desserts', 'order' => 4],
            ['name' => 'Beverages', 'slug' => 'beverages', 'order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
