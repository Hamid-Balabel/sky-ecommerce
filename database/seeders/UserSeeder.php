<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'password' => '123456',
            'email' => 'admin@gmail.com',
            'type' => UserTypeEnum::Admin->value,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Customer',
            'password' => '123456',
            'email' => 'customer@gmail.com',
            'type' => UserTypeEnum::Customer->value,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'test user',
            'password' => '123456',
            'email' => 'test@gmail.com',
            'type' => UserTypeEnum::Customer->value,
            'email_verified_at' => now(),
        ]);
    }
}
