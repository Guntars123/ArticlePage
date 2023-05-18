<?php declare(strict_types=1);

namespace App\Services\User;

use App\ApiClient;

class IndexUserService
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    public function execute(): array
    {
        return $this->apiClient->getUsers();
    }

}