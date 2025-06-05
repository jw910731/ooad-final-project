<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test1 User',
            'email' => 'test1@example.com',
            'password' => bcrypt('12345678'),
            'system_admin' => false,
        ]);
        User::factory()->create([
            'name' => 'Test2 User',
            'email' => 'test2@example.com',
            'password' => bcrypt('12345678'),
            'system_admin' => false,
        ]);
        User::factory()->create([
            'name' => 'Test3 User',
            'email' => 'test3@example.com',
            'password' => bcrypt('12345678'),
            'system_admin' => false,
        ]);
        User::factory()->create([
            'name' => 'Test4 User',
            'email' => 'test4@example.com',
            'password' => bcrypt('12345678'),
            'system_admin' => false,
        ]);
        User::factory()->create([
            'name' => 'Test5 User',
            'email' => 'test5@example.com',
            'password' => bcrypt('12345678'),
            'system_admin' => false,
        ]);
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'),
            'system_admin' => true,
        ]);
    }
}
