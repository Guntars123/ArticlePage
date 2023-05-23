<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Cache;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class JsonPlaceholderUserRepository implements UserRepository
{
    private Client $client;
    private const BASE_URI = 'https://jsonplaceholder.typicode.com/';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function all(): array
    {
        if (!Cache::has('users')) {
            $usersResponse = $this->apiRequest(self::BASE_URI . "users/");
            Cache::remember('users', $usersResponse);
        } else {
            $usersResponse = Cache::get('users');
        }
        if (!empty($usersResponse)) {
            $responseData = json_decode($usersResponse);
            $users = [];
            foreach ($responseData as $user) {
                $users[] = $this->buildModel($user);
            }
            return $users;
        }
        return [];
    }

    public function getById(int $id): ?User
    {
        if (!Cache::has('user' . $id)) {
            $usersResponse = $this->apiRequest(self::BASE_URI . "users/" . $id);
            Cache::remember('user' . $id, $usersResponse);
        } else {
            $usersResponse = Cache::get('user' . $id);
        }
        if (!empty($usersResponse)) {
            $responseData = json_decode($usersResponse);
            return $this->buildModel($responseData);
        }
        return null;
    }

    private function buildModel(stdClass $user): User
    {
        return new User
        (
            $user->id,
            $user->name,
            $user->username,
            $user->email,
            $user->address,
            $user->phone,
            $user->website,
            $user->company
        );
    }

    private function apiRequest(string $url): string
    {
        try {
            $response = $this->client->get($url);
            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            return "";
        }
    }
}