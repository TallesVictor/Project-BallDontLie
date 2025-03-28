<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@example.com')->exists()){
            User::factory()->create([
                'name' => 'Admin - Test User',
                'email' => 'admin@example.com',
                'role_id' => Role::where('name', 'Admin')->first()->id
            ]);

            User::factory()->create([
                'name' => 'User - Test User',
                'email' => 'user@example.com',
                'role_id' => Role::where('name', 'Admin')->first()->id
            ]);
            
        }
        User::factory(10)->create();
    }
}
