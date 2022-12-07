<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            "name" => "Dafa",
            "email" => "Dafa@mail.com",
            "password" => Hash::make('12345678'),
        ]);

        User::create([
            "name" => "Dafa",
            "email" => "Dafa1@mail.com",
            "password" => Hash::make('12345678'),
        ]);

        // User::insert([
        //     "id_user" => "1",
        //     "name" => "Dafa",
        //     "email" => "Dafa1@mail.com",
        //     "password" => Hash::make('12345678'),
        // ]);
    }
}
