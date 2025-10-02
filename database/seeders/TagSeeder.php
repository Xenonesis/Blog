<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Laravel', 'PHP', 'JavaScript', 'React', 'Vue.js', 'Node.js',
            'Python', 'AI', 'Machine Learning', 'Web Development',
            'Mobile Apps', 'Design', 'UX/UI', 'SEO', 'Marketing',
            'Productivity', 'Tips', 'Tutorial', 'Review', 'News'
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
