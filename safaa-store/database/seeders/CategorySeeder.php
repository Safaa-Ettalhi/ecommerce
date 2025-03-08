<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Électronique',
            'Vêtements',
            'Livres',
            'Maison & Jardin',
            'Sports & Loisirs',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}

