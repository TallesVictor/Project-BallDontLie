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

    public function store(PlayerStoreRequest $request)
    {
        $playerService = new PlayerService();
        $playerDTO =  PlayerStoreDTO::fromArray($request->validated());

        $player = $playerService->createPlayer($playerDTO);

        return response()->json(
            PlayerResource::make($player)
        )->setStatusCode(201);
    }

 
    public function show(Player $player)
    {
        return response()->json(
            PlayerResource::make($player)
        );
    }

   
    public function update(Player $player, PlayerStoreRequest $request)
    {
        $playerService = new PlayerService();
        $playerDTO =  PlayerStoreDTO::fromArray($request->validated());

        $player = $playerService->editPlayer($playerDTO, $player);

        return response()->json(
            PlayerResource::make($player)
        )->setStatusCode(201);
    }

    public function destroy(Player $player)
    {
        $player->delete();

        return response()->json(['message' => 'Player deleted'], 200);
    }
}
