<?php declare(strict_types=1);

namespace App\Services\User\Show;

use App\ApiClient;
use App\Exceptions\ResourceNotFoundException;

class ShowUserService
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    public function execute(ShowUserRequest $request): ShowUserResponse
    {
        $user = $this->apiClient->getSingleUser($request->getUserId());

        if ($user == null) {
            throw new ResourceNotFoundException("User by id {$request->getUserId()} not found");
        }

        $articles = $this->apiClient->getArticlesByUserId($user->getId());

        return new ShowUserResponse($user,$articles);
    }
}
