<?php

namespace Tests\Unit;

use App\DTOs\PlayerIndexDTO;
use App\Services\PlayerService;
use App\DTOs\PlayerStoreDTO;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Helpers\Services\PlayerServiceTestHelper;
use Tests\TestCase;

class PlayerServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $playerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->playerService = new PlayerService();
    }

    public function testCreatePlayerWithValidTeam()
    {
        $team = Team::factory()->create();

        $playerArray = PlayerServiceTestHelper::getPlayerArray($team->full_name);
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);

        $player = $this->playerService->createPlayer($playerDTO);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals($team->id, $player->team_id);
    }

    public function testCreatePlayerWithInvalidTeam()
    {
        $playerArray = PlayerServiceTestHelper::getPlayerArray('PHPUNIT-INVALIDTEAM' . random_int(1, 1000));
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);

        $this->expectException(\Exception::class);
        $this->playerService->createPlayer($playerDTO);
    }

    public function testCreatePlayerWithNullTeam()
    {
        $playerArray = PlayerServiceTestHelper::getPlayerArray();
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);

        $this->expectException(\Exception::class);
        $this->playerService->createPlayer($playerDTO);
    }

    public function testCreatePlayerWithNullPlayerDTO()
    {
        $this->expectException(\TypeError::class);
        $this->playerService->createPlayer(null);
    }

    public function testListPlayersWithValidPagination()
    {
        $playerService = new PlayerService();
        $dto = new PlayerIndexDTO(1, 10);

        $players = $playerService->listPlayers($dto);

        $this->assertIsArray($players);
        $this->assertCount(10, $players);
    }

    public function testListPlayersWithInvalidPageValue()
    {
        $playerService = new PlayerService();
        $dto = new PlayerIndexDTO('abc', 10);

        $this->expectException(\TypeError::class);
        $playerService->listPlayers($dto);
    }

    public function testListPlayersWithInvalidPerPageValue()
    {
        $playerService = new PlayerService();
        $dto = new PlayerIndexDTO(1, 'abc');

        $this->expectException(\TypeError::class);
        $playerService->listPlayers($dto);
    }

    public function testEditPlayerWithValidTeam()
    {
        $team = Team::factory()->create();
        $player = Player::factory()->create();

        $playerArray = PlayerServiceTestHelper::getPlayerArray();
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);
        $playerDTO->first_name = 'John';
        $playerDTO->last_name = 'Doe';
        $playerDTO->team_full_name = $team->full_name;

        $editedPlayer = $this->playerService->editPlayer($playerDTO, $player);
        $this->assertEquals($team->id, $editedPlayer->team_id);
        $this->assertEquals('John', $editedPlayer->first_name);
        $this->assertEquals('Doe', $editedPlayer->last_name);
    }

    
    public function testEditPlayerWithInvalidTeam()
    {
        $player = Player::factory()->create();
       
        $playerArray = PlayerServiceTestHelper::getPlayerArray();
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);
        $playerDTO->team_full_name = 'PHPUNIT-INVALIDTEAM' . random_int(1, 1000);
        
        $this->expectException(\Exception::class);
        $this->playerService->editPlayer($playerDTO, $player);
    }
    
    public function testEditPlayerWithNullTeam()
    {
        $player = Player::factory()->create();
       
        $playerArray = PlayerServiceTestHelper::getPlayerArray();
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);
        $playerDTO->team_full_name = '';

        $this->expectException(\Exception::class);
        $this->playerService->editPlayer($playerDTO, $player);
    }
   
    public function testEditPlayerWithNullPlayerDTO()
    {
        $player = Player::factory()->create();
        $this->expectException(\TypeError::class);
        $this->playerService->editPlayer(null, $player);
    }

    public function testEditPlayerWithNullPlayer()
    {
        $playerArray = PlayerServiceTestHelper::getPlayerArray();
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);

        $this->expectException(\TypeError::class);
        $this->playerService->editPlayer($playerDTO, null);
    }
}
