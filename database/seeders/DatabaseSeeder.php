<?php

namespace Database\Seeders;

use App\Models\Data;
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
        User::factory()->admin()->create([
            'username' => 'admin',
            'password' => bcrypt('Password@123'),
        ]);

        Data::factory(50)->recycle([
            User::factory(14)->create()
        ])->create();
    }
}
