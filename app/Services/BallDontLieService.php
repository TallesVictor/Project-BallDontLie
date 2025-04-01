<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Repositories\SettingRepository;
use App\Traits\ApiConnectorBallDontLieTrait;
use Illuminate\Support\Facades\Cache;

class BallDontLieService
{
    use ApiConnectorBallDontLieTrait;

    protected $client;
    protected $token;
    protected $baseUrl;
    protected $timeCache;


    public function __construct()
    {
        $this->setupApi();
        $this->timeCache = now()->addHours(12);
    }

    public function getTeams()
    {
        return $this->get('/teams');
    }

    public function getPlayers(int $cursor = 0)
    {
        return $this->get('/players?per_page=100&cursor=' . $cursor);
    }

    public function getGames(int $cursor = 0)
    {
        return $this->get('/games?seasons[]=2024&per_page=100&cursor=' . $cursor);
    }
    
    public function listAllPlayers(): array
    {
        return $this->listAllEntities('player', fn($cursor) => $this->getPlayers($cursor));
    }

    public function listAllGames(): array
    {
        return $this->listAllEntities('game', fn($cursor) => $this->getGames($cursor));
    }
    
}
