<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\Team;
use App\Services\BallDontLieService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncPlayersFromApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-players-from-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync players from api';

    private $sizeChunk = 500;
    private $sizeSearch = 2000;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output->title('Syncing players from api');


        Player::truncate();
        Cache::clear();


        $this->syncPlayers();

        Cache::forget('cursor.player');
        $this->output->title('Finish');
    }

    /**
     * Sync players from BallDontLie API.
     *
     * This method retrieves the list of all players from the BallDontLie API, mounts the player objects to
     * be inserted in database and inserts them in chunks.
     */
    private function syncPlayers(): void
    {
        $allPlayers = $this->listAllPlayers();

        $arrayPlayers = $this->mountPlayers($allPlayers);

        $this->insertPlayersInChunks($arrayPlayers);
    }

    /**
     * Retrieve a list of all players from the BallDontLie API.
     *
     * @return array List of players retrieved from the API.
     */
    private function listAllPlayers(): array
    {
        $ballDontLieService = new BallDontLieService();

        return $ballDontLieService->listAllPlayers();
    }

    /**
     * Mount the player objects to insert in database.
     *
     * @param array $allPlayers The players objects from BallDontLie API.
     *
     * @return array The player objects ready to be inserted in database.
     */
    private function mountPlayers(array $allPlayers): array
    {
        $teams = Team::pluck('id', 'team_origin_id')->toArray();

        $arrayPlayers = [];
        foreach ($allPlayers as $player) {
            $this->mountObjectPlayer($player, $teams);
            $arrayPlayers[] = (array) $player;
        }

        return $arrayPlayers;
    }

    /**
     * Insert players in chunks.
     *
     * @param  array  $arrayPlayers List of players to be inserted.
     * @return void
     */
    private function insertPlayersInChunks(array $arrayPlayers): void
    {
        $allPlayersChunk = array_chunk($arrayPlayers, $this->sizeChunk);
        
        foreach ($allPlayersChunk as $playersChunk) {
            Player::insert($playersChunk);
        }
    }

    /**
     * Mount the player object to insert in database.
     *
     * @param object $player The player object from BallDontLie API.
     * @param array $teams The teams plucked by origin id.
     *
     * @return void
     */
    private function mountObjectPlayer(object $player, array $teams): void
    {
        $teamId = $player->team->id;
        unset($player->team);
        unset($player->id);

        $player->weight = (int) $player->weight;
        $player->team_id = $teams[$teamId];
        $player->created_at = now();
        $player->updated_at = now();
    }
}
