<?php

namespace Tests\Helpers\Services;

class PlayerServiceTestHelper
{
    public static function getPlayerArray($teamFullName = '')
    {
        return   [
            'team_full_name' => $teamFullName,
            'first_name' => "Teste Unitário",
            'last_name' => "Teste",
            'position' => "G",
            'height' => "6-6",
            'weight' => "190",
            'jersey_number' => "8",
            'college' => "FC Barcelona",
            'country' => "Spain",
            'draft_year' => 2013,
            'draft_round' => 2,
            'draft_number' => 32,

        ];
    }
}