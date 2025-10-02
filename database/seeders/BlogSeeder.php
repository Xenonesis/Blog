<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->info('No users or categories found. Please run the other seeders first.');
            return;
        }

        $techCategory = $categories->where('name', 'Technology')->first();
        $lifestyleCategory = $categories->where('name', 'Lifestyle')->first();
        $travelCategory = $categories->where('name', 'Travel')->first();
        
        $adminUser = $users->where('role', 'admin')->first();
        $regularUser = $users->where('role', 'user')->first();

        $sampleBlogs = [
            [
                'title' => 'Getting Started with Laravel 11',
                'excerpt' => 'Learn the basics of Laravel 11 and build your first web application.',
                'content' => 'Laravel is a powerful PHP framework that makes web development enjoyable and creative. In this comprehensive guide, we\'ll explore the new features of Laravel 11 and walk through building a complete web application from scratch. We\'ll cover routing, controllers, models, views, and much more.',
                'category_id' => $techCategory ? $techCategory->id : $categories->first()->id,
                'user_id' => $adminUser ? $adminUser->id : $users->first()->id,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Modern PHP Development Best Practices',
                'excerpt' => 'Discover the latest best practices for PHP development in 2024.',
                'content' => 'PHP has evolved significantly over the years. Modern PHP development involves using composer for dependency management, following PSR standards, implementing proper testing strategies, and leveraging the latest language features. This article covers everything you need to know about contemporary PHP development.',
                'category_id' => $techCategory ? $techCategory->id : $categories->first()->id,
                'user_id' => $regularUser ? $regularUser->id : $users->skip(1)->first()->id,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Building RESTful APIs with Laravel',
                'excerpt' => 'A complete guide to creating robust RESTful APIs using Laravel.',
                'content' => 'APIs are the backbone of modern web applications. Laravel provides excellent tools for building RESTful APIs quickly and efficiently. In this tutorial, we\'ll create a complete API with authentication, validation, error handling, and proper HTTP status codes.',
                'category_id' => $techCategory ? $techCategory->id : $categories->first()->id,
                'user_id' => $adminUser ? $adminUser->id : $users->first()->id,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'The Art of Work-Life Balance',
                'excerpt' => 'Tips and strategies for maintaining a healthy work-life balance.',
                'content' => 'In today\'s fast-paced world, maintaining a healthy work-life balance is more important than ever. This article explores practical strategies for managing your time, setting boundaries, and ensuring that you have time for both professional growth and personal fulfillment.',
                'category_id' => $lifestyleCategory ? $lifestyleCategory->id : $categories->skip(1)->first()->id,
                'user_id' => $regularUser ? $regularUser->id : $users->skip(1)->first()->id,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Exploring the Hidden Gems of Europe',
                'excerpt' => 'Discover breathtaking destinations off the beaten path in Europe.',
                'content' => 'Europe is full of incredible destinations that go beyond the typical tourist spots. From charming medieval towns to pristine natural landscapes, this guide takes you through some of Europe\'s best-kept secrets that offer authentic experiences and unforgettable memories.',
                'category_id' => $travelCategory ? $travelCategory->id : $categories->skip(2)->first()->id,
                'user_id' => $adminUser ? $adminUser->id : $users->first()->id,
                'is_published' => true,
                'published_at' => now()->subHours(12),
            ],
        ];

        foreach ($sampleBlogs as $blogData) {
            $blog = \App\Models\Blog::create($blogData);
            
            // Attach random tags to each blog
            $randomTags = $tags->random(rand(2, 4))->pluck('id');
            $blog->tags()->attach($randomTags);
        }
    }
}
