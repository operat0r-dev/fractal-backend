<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\IntegrationSetting;
use App\Models\User;
use GuzzleHttp\Client;

class TrelloService
{
    protected $client;
    protected $apiKey;
    protected $token;
    protected User $user;
    protected bool $isConfigured = false;

    public function __construct(User $user)
    {
        $this->user = $user;

        $key = IntegrationSetting::where('type', 'Trello api key')->where('application_id', $this->user->application_id)->first();
        $token = IntegrationSetting::where('type', 'Trello api token')->where('application_id', $this->user->application_id)->first();
        if ($key->getValue() && $token->getValue()) {
            $this->client = new Client(['base_uri' => 'https://api.trello.com/1/']);
            $this->apiKey = $key->getValue();
            $this->token = $token->getValue();
            $this->isConfigured = true;
        }
    }

    public function getBoards()
    {
        $response = $this->client->get("members/me/boards?key={$this->apiKey}&token={$this->token}");
        $body = $response->getBody()->getContents();
        $boards = json_decode($body, true);

        return $boards;
    }

    public function createCard(int $listId, string $name, string $description)
    {
        $response = $this->client->post("cards", [
            'query' => [
                'key' => $this->apiKey,
                'token' => $this->token,
                'idList' => $listId,
                'name' => $name,
                'desc' => $description
            ]
        ]);
        $body = $response->getBody()->getContents();

        return json_decode($body, true);
    }

    public function createReportListIfNotExists(int $boardId, string $listName)
    {
        $lists = $this->getLists($boardId);
        foreach ($lists as $list) {
            if ($list['name'] == $listName) {
                return $list['id'];
            }
        }

        $response = $this->client->post("lists", [
            'query' => [
                'key' => $this->apiKey,
                'token' => $this->token,
                'idBoard' => $boardId,
                'name' => $listName
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getLists(int $boardId)
    {
        $response = $this->client->get("boards/{$boardId}/lists", [
            'query' => [
                'key' => $this->apiKey,
                'token' => $this->token,
            ]
        ]);
        $body = $response->getBody()->getContents();
        return json_decode($body, true);
    }

    public function isConfigured()
    {
        return $this->isConfigured;
    }
}
