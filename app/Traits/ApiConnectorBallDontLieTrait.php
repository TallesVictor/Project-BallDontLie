<?php

namespace App\Traits;

use App\Repositories\SettingRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

trait ApiConnectorBallDontLieTrait
{
    protected $client;
    protected $baseUrl;
    protected $headers;


    protected function setupApi()
    {
        $settingRepository = app(SettingRepository::class);

        $token = $settingRepository->findByName('key_balldontlie');
        $this->baseUrl = $settingRepository->findByName('base_url_balldontlie');

        $this->client = new Client([
            'headers' => [
                'Authorization' => $token,
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function get(string $endpoint, array $params = [], int $retry = 0)
    {
        try {
            $response = $this->client->get($this->baseUrl . $endpoint);

            if ($retry > 3) return [];
            
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $exception) {
            if ($exception->getCode() === 429) {
                sleep(60);
                return $this->get($endpoint,$params, ++$retry);
            }
            throw $exception;
        }
    }

    public function listAllEntities(string $entity, callable $fetchFunction): array
    {
        $allEntities = [];
        $cursor = Cache::get("cursor.$entity", 0);

        do {
            $response = $fetchFunction($cursor);
            $allEntities = array_merge($allEntities, $response->data);

            if (empty($response->data) || count($response->data) < 100) {
                break;
            }
            $cursor = $response->meta?->next_cursor;

            if (is_null($cursor)) {
                break;
            }

            Cache::put("cursor.$entity", $cursor, $this->timeCache);
        } while (true);
        
        return $allEntities;
    }


}
