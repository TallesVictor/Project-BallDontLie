<?php
namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function findByName(string $name)
    {
        $value= Setting::where('name', $name)->first()->value;
        return json_decode($value);
    }
}
