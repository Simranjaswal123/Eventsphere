<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@eventsphere.com'],
            [
                'name'      => 'EventSphere Admin',
                'username'  => 'eventsphere',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'is_active' => true,
                'bio'       => 'Official EventSphere account. Curating the best local events.',
                'location'  => 'Mumbai, India',
            ]
        );

        // Create demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@eventsphere.com'],
            [
                'name'      => 'Alex Demo',
                'username'  => 'alexdemo',
                'password'  => Hash::make('password'),
                'role'      => 'user',
                'is_active' => true,
                'bio'       => 'Event enthusiast and community organizer.',
                'location'  => 'Delhi, India',
            ]
        );

        $categories = Category::pluck('_id', 'slug');
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Hyderabad', 'Pune', 'Kolkata'];

        $events = [
            ['title' => 'Indie Music Fest 2025', 'category' => 'music', 'city' => 'Mumbai', 'is_free' => false, 'price' => 499, 'days' => 7, 'featured' => true],
            ['title' => 'Laravel & PHP Workshop', 'category' => 'technology', 'city' => 'Bangalore', 'is_free' => true, 'price' => 0, 'days' => 3, 'featured' => true],
            ['title' => 'Community 5K Fun Run', 'category' => 'sports', 'city' => 'Delhi', 'is_free' => true, 'price' => 0, 'days' => 5, 'featured' => false],
            ['title' => 'Street Art Exhibition', 'category' => 'arts', 'city' => 'Pune', 'is_free' => true, 'price' => 0, 'days' => 10, 'featured' => true],
            ['title' => 'Food Truck Festival', 'category' => 'food-drink', 'city' => 'Hyderabad', 'is_free' => false, 'price' => 199, 'days' => 2, 'featured' => false],
            ['title' => 'AI & Machine Learning Summit', 'category' => 'technology', 'city' => 'Bangalore', 'is_free' => false, 'price' => 999, 'days' => 14, 'featured' => true],
            ['title' => 'Startup Networking Night', 'category' => 'networking', 'city' => 'Mumbai', 'is_free' => true, 'price' => 0, 'days' => 4, 'featured' => false],
            ['title' => 'Yoga & Wellness Retreat', 'category' => 'health', 'city' => 'Chennai', 'is_free' => false, 'price' => 799, 'days' => 20, 'featured' => false],
            ['title' => 'Comedy Open Mic Night', 'category' => 'comedy', 'city' => 'Delhi', 'is_free' => false, 'price' => 299, 'days' => 6, 'featured' => false],
            ['title' => 'Nature Hiking Trail Day', 'category' => 'outdoors', 'city' => 'Pune', 'is_free' => true, 'price' => 0, 'days' => 8, 'featured' => false],
            ['title' => 'Business Pitch Competition', 'category' => 'business', 'city' => 'Mumbai', 'is_free' => false, 'price' => 599, 'days' => 12, 'featured' => false],
            ['title' => 'Community Clean-Up Drive', 'category' => 'community', 'city' => 'Kolkata', 'is_free' => true, 'price' => 0, 'days' => 3, 'featured' => false],
        ];

        foreach ($events as $i => $e) {
            $catId = (string) ($categories[$e['category']] ?? $categories->first());
            $startDate = now()->addDays($e['days'])->setHour(rand(9, 19))->setMinute(0);

            Event::firstOrCreate(
                ['slug' => Str::slug($e['title']) . '-' . Str::random(4)],
                [
                    'title'        => $e['title'],
                    'description'  => "Join us for an incredible {$e['title']} experience! This is a community event designed to bring people together, share knowledge, and create lasting connections. We welcome participants of all backgrounds and experience levels. Come prepared to have fun, learn, and meet amazing people from your community.\n\nDoors open 30 minutes before the event. Light refreshments will be available.",
                    'category_id'  => $catId,
                    'user_id'      => (string) ($i % 3 === 0 ? $admin->_id : $user->_id),
                    'location'     => 'Community Center Hall ' . ($i + 1),
                    'address'      => ($i + 1) . ' Main Street',
                    'city'         => $e['city'],
                    'state'        => '',
                    'country'      => 'India',
                    'start_date'   => $startDate,
                    'end_date'     => $startDate->copy()->addHours(rand(2, 5)),
                    'is_free'      => $e['is_free'],
                    'price'        => $e['price'],
                    'max_attendees'=> rand(50, 500),
                    'tags'         => [strtolower(str_replace(' ', '-', $e['title'])), $e['city'], $e['category']],
                    'status'       => 'published',
                    'is_featured'  => $e['featured'],
                    'views_count'  => rand(10, 500),
                    'likes_count'  => rand(2, 50),
                    'rsvp_count'   => rand(1, 30),
                ]
            );
        }

        $this->command->info('Events seeded! Demo credentials: admin@eventsphere.com / password');
    }
}
