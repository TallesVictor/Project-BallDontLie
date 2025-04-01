<?php

namespace App\Repositories;

use App\Models\Player;

class PlayerRepository
{
    public function findByFirstName(string $firstName): Player
    {
        return Player::where('first_name', $firstName)->firstOrFail();
    }
}
