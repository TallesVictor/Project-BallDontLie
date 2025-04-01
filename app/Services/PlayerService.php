<?php

namespace App\Services;

use App\DTOs\PlayerIndexDTO;
use App\DTOs\PlayerStoreDTO;
use App\Models\Player;
use App\Repositories\TeamRepository;

class PlayerService
{

    protected $teamRepository;

    public function __construct()
    {
        $this->teamRepository = app(TeamRepository::class);
    }

    /**
     * Create a new player.
     *
     * @param  PlayerStoreDTO  $playerDTO
     * @return Player
     */
    public function createPlayer(PlayerStoreDTO $playerDTO)
    {
        $team =  $this->teamRepository->findByFullName($playerDTO->team_full_name);

        $player = new Player($playerDTO->toArray());
        $player->team_id = $team->id;
        $player->save();

        return $player;
    }

    /**
     * List players with pagination.
     *
     * @param  PlayerIndexDTO  $dto  Data transfer object containing pagination details.
     * @return array  List of players for the specified page and per-page limit.
     */
    public function listPlayers(PlayerIndexDTO $dto)
    {
        $page = $dto->page;
        $perPage = $dto->per_page;
        $players = Player::paginate($perPage, ['*'], 'page', $page);

        return $players->items();
    }

    /**
     * Edit a player.
     *
     * @param  Player  $player  Player to be edited.
     * @return Player  Edited player.
     */
    public function editPlayer(PlayerStoreDTO $playerDTO, Player $player)
    {
        $team = $this->teamRepository->findByFullName($playerDTO->team_full_name);

        $player->fill($playerDTO->toArray());

        $player->team_id = $team->id;
        $player->save();

        return $player;
    }
}
