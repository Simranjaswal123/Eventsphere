<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Music',        'icon' => '🎵', 'color' => '#E11D48'],
            ['name' => 'Technology',   'icon' => '💻', 'color' => '#2563EB'],
            ['name' => 'Sports',       'icon' => '⚽', 'color' => '#16A34A'],
            ['name' => 'Arts',         'icon' => '🎨', 'color' => '#7C3AED'],
            ['name' => 'Food & Drink', 'icon' => '🍕', 'color' => '#EA580C'],
            ['name' => 'Education',    'icon' => '📚', 'color' => '#0891B2'],
            ['name' => 'Networking',   'icon' => '🤝', 'color' => '#0D9488'],
            ['name' => 'Health',       'icon' => '🏋️', 'color' => '#65A30D'],
            ['name' => 'Business',     'icon' => '💼', 'color' => '#CA8A04'],
            ['name' => 'Community',    'icon' => '🏘️', 'color' => '#DC2626'],
            ['name' => 'Comedy',       'icon' => '😂', 'color' => '#D97706'],
            ['name' => 'Outdoors',     'icon' => '🌿', 'color' => '#059669'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                array_merge($cat, ['slug' => Str::slug($cat['name']), 'is_active' => true])
            );
        }

        $this->command->info('Categories seeded!');
    }
}
