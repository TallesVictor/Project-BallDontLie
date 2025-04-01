<?php

namespace App\Http\Controllers;

use App\DTOs\PlayerIndexDTO;
use App\DTOs\PlayerStoreDTO;
use App\Http\Requests\Auth\PlayerIndexRequest;
use App\Http\Requests\Auth\PlayerStoreRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Services\PlayerService;

class PlayerController extends Controller
{

    /**
     * Display a listing of players with pagination.
     *
     * @param  PlayerIndexRequest  $request  The request containing pagination parameters.
     * @return array<string, mixed>  An array containing the paginated list of players and pagination metadata.
     */
    public function index(PlayerIndexRequest $request)
    {
        $playerService = new PlayerService();

        $dto = PlayerIndexDTO::fromArray($request->validated());
        $players = $playerService->listPlayers($dto);

        return [
            'data' => PlayerResource::collection($players),
            'meta' => [
                'page' => $request->page,
                'per_page' => $request->per_page,
            ]
        ];
    }

    /**
     * Store a newly created player in storage.
     *
     * @param  PlayerStoreRequest  $request  The request containing player data.
     * @return \Illuminate\Http\JsonResponse  The JSON response containing the created player resource.
     */
    public function store(PlayerStoreRequest $request)
    {
        $playerService = new PlayerService();
        $playerDTO =  PlayerStoreDTO::fromArray($request->validated());

        $player = $playerService->createPlayer($playerDTO);

        return response()->json(
            PlayerResource::make($player)
        )->setStatusCode(201);
    }



    /**
     * Display the specified player.
     *
     * @param  Player  $player  The player instance to display.
     * @return \Illuminate\Http\JsonResponse  The JSON response containing the player resource.
     */
    public function show(Player $player)
    {
        return response()->json(
            PlayerResource::make($player)
        );
    }


    /**
     * Update the specified player in storage.
     *
     * @param  Player  $player  The player instance to update.
     * @param  PlayerStoreRequest  $request  The request containing player data.
     * @return \Illuminate\Http\JsonResponse  The JSON response containing the updated player resource.
     */
    public function update(Player $player, PlayerStoreRequest $request)
    {
        $playerService = new PlayerService();
        $playerDTO =  PlayerStoreDTO::fromArray($request->validated());

        $player = $playerService->editPlayer($playerDTO, $player);

        return response()->json(
            PlayerResource::make($player)
        )->setStatusCode(200);
    }

    /**
     * Remove the specified player from storage.
     *
     * @param  Player  $player  The player instance to delete.
     * @return \Illuminate\Http\JsonResponse  The JSON response containing the deletion message.
     */
    public function destroy(Player $player)
    {
        $player->delete();

        return response()->json(['message' => 'Player deleted'], 200);
    }
}
