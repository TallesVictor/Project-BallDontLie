<?php

namespace Tests\Feature;

use App\DTOs\PlayerStoreDTO;
use App\Http\Controllers\PlayerController;
use App\Http\Requests\Auth\PlayerIndexRequest;
use App\Http\Requests\Auth\PlayerStoreRequest;
use App\Models\Player;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Repositories\PlayerRepository;
use App\Services\PlayerService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\Helpers\Services\PlayerServiceTestHelper;
use Tests\TestCase;

class PlayerControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testSuccessfulResponseWithValidRequestData()
    {
        $user = User::factory()->create();
        Player::factory(3)->create();

        $response = $this->actingAs($user)->getJson('/api/player?page=1&per_page=3');

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertIsArray($data['data']);
        $this->assertCount(3, $data['data']);
        $this->assertEquals(3, $data['meta']['per_page']);
    }

    public function testErrorResponseWithInvalidPaginationValues()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/player?page=abc&per_page=3');
        $response->assertStatus(422);
    }

    public function testErrorResponseWithMissingPaginationValues()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/player');
        $response->assertStatus(422);
    }

    public function testStorePlayerWithValidRequestData()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        $playerArray = PlayerServiceTestHelper::getPlayerArray();
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);
        $playerDTO->team_full_name = $team->full_name;
        $playerDTO->first_name = 'PHPUNITTEST - Play for test';

        $data = $playerDTO->toArray();

        $response = $this->actingAs($user)->postJson('/api/player', $data);
        $player = app(PlayerRepository::class)->findByFirstName('PHPUNITTEST - Play for test');

        $response->assertStatus(201);
        $this->assertJson($response->getContent());
        $this->assertEquals($player->first_name,  $playerDTO->first_name);
    }

    public function testStorePlayerWithInvalidRequestData()
    {
        $user = User::factory()->create();
        $data = [
            'first_name' => '',
        ];
        $response = $this->actingAs($user)->postJson('/api/player', $data);

        $response->assertStatus(422);
        $this->assertJson($response->getContent());
    }

    public function testStorePlayerWithNullRequestData()
    {
        $user = User::factory()->create();
        $data = [];

        $response = $this->actingAs($user)->postJson('/api/player', $data);

        $response->assertStatus(422);
        $this->assertJson($response->getContent());
    }

    public function testShowReturnsJsonResponse()
    {
        $user = User::factory()->create();
        $player = Player::factory()->create();
        $data = [];

        $response = $this->actingAs($user)->getJson('/api/player/' . $player->id);
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertEquals($player->id, $data['id']);
        $this->assertEquals($player->first_name, $data['first_name']);
    }

    public function testShowReturnsPlayerNotFound()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/player/-999');
        $response->assertStatus(404);
    }

    public function testSuccessfulUpdate()
    {
        $user = User::factory()->create();
        $player = Player::factory()->create();
        $team = Team::factory()->create();

        $playerArray = PlayerServiceTestHelper::getPlayerArray();
        $playerDTO = PlayerStoreDTO::fromArray($playerArray);
        $playerDTO->team_full_name = $team->full_name;

        $data = $playerDTO->toArray();

        $response = $this->actingAs($user)->putJson('/api/player/' . $player->id, $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('players', [
            'first_name' => $playerArray['first_name'],
            'last_name' => $playerArray['last_name'],
            'team_id' => $team->id,
        ]);
    }

    public function testUpdateWithInvalidPlayerData()
    {
        $user = User::factory()->create();
        $player = Player::factory()->create();

        $data = ['first_name_x' => ''];

        $response = $this->actingAs($user)->putJson('/api/player/' . $player->id, $data);

        $response->assertStatus(422);
    }

    public function testUpdateUserNotFound()
    {
        $user = User::factory()->create();

        $data = ['first_name_x' => ''];

        $response = $this->actingAs($user)->putJson('/api/player/-999', $data);

        $response->assertStatus(404);
    }

    public function testDeleteSuccessful()
    {
        $user =  User::where('email', 'test@example.com')->first();

        $player = Player::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/api/player/' . $player->id);

        $response->assertStatus(200);
    }

    public function testDeleteUserNotPermission()
    {
        $user =  User::factory()->create([
            'name' => 'phpunittest Admin - Test User',
            'email' => 'phpunittest@example.com',
            'role_id' => Role::where('name', 'Admin')->first()->id
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/player/-999');

        $response->assertStatus(404);
    }

    public function testDeleteUserNotFound()
    {
        $user =  User::factory()->create([
            'name' => 'phpunittest Admin - Test User',
            'email' => 'phpunittest@example.com',
            'role_id' => Role::where('name', 'User')->first()->id
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/player/-999');

        $response->assertStatus(404);
    }
}
