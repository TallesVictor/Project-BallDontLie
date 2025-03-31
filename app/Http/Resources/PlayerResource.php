<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $player= parent::toArray($request);
        unset($player['team_id']);

        $player['team'] = TeamResource::make($this->team);
        
        return $player;
    }
}
