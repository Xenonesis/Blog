<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'description' => 'Articles about technology, programming, and software development',
            ],
            [
                'name' => 'Lifestyle',
                'description' => 'Posts about lifestyle, health, and personal development',
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel experiences, tips, and destination guides',
            ],
            [
                'name' => 'Food',
                'description' => 'Recipes, restaurant reviews, and culinary adventures',
            ],
            [
                'name' => 'Business',
                'description' => 'Business insights, entrepreneurship, and career advice',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
