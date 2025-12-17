<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        for ($i = 1; $i <= 50; $i++) {
            DB::table('users')->insert([
                'username' => $faker->unique()->userName,
                'email' => $faker->unique()->email,
                'password' => Hash::make('password123'),
                'role' => $faker->randomElement(['admin', 'user', 'moderator']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
