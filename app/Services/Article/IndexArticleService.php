<?php declare(strict_types=1);

namespace App\Services\Article;

use App\ApiClient;

class IndexArticleService
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    public function execute(): array
    {
        return $this->apiClient->getArticles();
    }
}