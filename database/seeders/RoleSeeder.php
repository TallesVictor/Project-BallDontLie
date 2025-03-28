<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => 'Admin'], 
            ['description' => 'Can do everything'] 
        );
        
        Role::firstOrCreate(
            ['name' => 'User'], 
            ['description' => 'Can not delete'] 
        );
        
    }
}
