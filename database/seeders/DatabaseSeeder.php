<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create some users
        $users = User::factory()->count(10)->create();

        // Create some categories
        $categories = Category::factory()->count(5)->create();

        // Create some blogs and associate each with a user and category
        foreach ($users as $user) {
            Blog::factory()->count(3)->for($user)->for($categories->random())->create();
        }
    }
}
