<?php

namespace App\Repositories;

use App\Models\Team;

class TeamRepository
{
    public function findByFullName(string $fullName): Team
    {
        return Team::where('full_name', $fullName)->firstOrFail();
    }
}
