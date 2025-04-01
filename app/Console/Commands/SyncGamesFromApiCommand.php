<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Team;
use App\Services\BallDontLieService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncGamesFromApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-games-from-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $sizeChunk = 500;
    private $sizeSearch = 2000;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output->title('Syncing games from api');

        $this->syncGames();

        Cache::forget('cursor.game');
        $this->output->title('Finish');
    }

    /**
     * Sync games from BallDontLie API.
     *
     * This method retrieves the list of all games from the BallDontLie API,
     * mounts the game objects to be inserted in database and inserts them in chunks.
     */
    private function syncGames()
    {
        $allGames = $this->listAllGames();

        $arrayGames = $this->mountGames($allGames);
        $this->insertGamesInChunks($arrayGames);
    }


    /**
     * Retrieve a list of all games from the BallDontLie API.
     *
     * @return array List of games retrieved from the API.
     */
    private function listAllGames()
    {
        $ballDontLieService = new BallDontLieService();
        return $ballDontLieService->listAllGames();
    }

    /**
     * Insert games in chunks.
     *
     * @param  array  $games The list of games to be inserted in the database.
     * @return void
     */
    private function insertGamesInChunks($games)
    {
        $allGamesChunk = array_chunk($games, $this->sizeChunk);

        foreach ($allGamesChunk as $gamesChunk) {
            Game::insert($gamesChunk);
        }

        if (count($games) < $this->sizeSearch || count($games) == 0) {
            return;
        }

        $this->syncGames();
    }

    /**
     * Make a array of Games to insert in database.
     * 
     * @param array $allGames
     * @return array
     */
    private function  mountGames($allGames)
    {
        $games = Team::pluck('id', 'team_origin_id')->toArray();

        $arrayGames = [];
        foreach ($allGames as $game) {

            $this->makeGame($game, $games);

            $arrayGames[] = (array) $game;
        }

        return $arrayGames;
    }

    /**
     * Create a game array with the necessary data to be inserted in the Game model.
     * 
     * @param object $game The game object with the home_team and visitor_team objects.
     * @param array $games Array with the team id as key and the corresponding id in the db as value.
     * 
     * @return void
     */
    private function makeGame(object $game, array $games)
    {
        $homeTeamId = $game->home_team->id;
        $visitorTeamId = $game->visitor_team->id;
        $dateTime = strtotime($game->datetime);

        unset($game->id);
        unset($game->home_team);
        unset($game->visitor_team);

        $game->datetime =  date('Y-m-d H:i:s', $dateTime);
        $game->home_team_id = $games[$homeTeamId];
        $game->visitor_team_id = $games[$visitorTeamId];
        $game->created_at = now();
        $game->updated_at = now();
    }
}
