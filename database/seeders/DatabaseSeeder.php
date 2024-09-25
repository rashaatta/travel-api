<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create(
            [
                'name' => 'Rasha Atta',
                'email' => 'rashaatta83@gmail.com',
                'password' => Hash::make('password'),
            ]
        );
        $this->call([RoleSeeder::class]);
    }
}
