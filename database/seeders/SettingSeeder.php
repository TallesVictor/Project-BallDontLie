<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert(
            [
                [
                    'group' => 'Hidden',
                    'name' => 'key_balldontlie',
                    'value' => '"eabe2808-4427-4606-832d-c83bf8f1cbc3"',
                ],
                [
                    'group' => 'Hidden',
                    'name' => 'base_url_balldontlie',
                    'value' => '"https://api.balldontlie.io/v1"',
                ]
            ]
        );
    }
}
